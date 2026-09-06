<?php
/**
 * Freshness indicator column in post list table.
 *
 * @package WPCity\ContentFreshness\Admin
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness\Admin;

defined( 'ABSPATH' ) || exit;

use WPCity\ContentFreshness\Config;
use WPCity\PluginBase\Ingredient_Interface;

/**
 * Adds a column showing freshness status per post.
 *
 * @since 1.0.0
 */
class Freshness_Column implements Ingredient_Interface {

	private const COLUMN_ID = 'wpcity_cf';

	private const META_LAST_REVIEWED = 'wpcity_cf_last_reviewed';

	/**
	 * Initialize the ingredient.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register_columns' ] );
		add_action( 'pre_get_posts', [ $this, 'handle_sorting' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	/**
	 * Register the column hooks for every tracked post type.
	 *
	 * Deliberately deferred to 'init' rather than done straight from init().
	 * Ingredients are constructed and initialised on 'plugins_loaded' at
	 * priority 10, while the Pro add-on registers its own on the same hook at
	 * priority 20. Resolving the post types any earlier means a post type that
	 * Pro or a third party enables never gets its column, because the hook
	 * names were already fixed.
	 *
	 * 'init' also fires on admin-ajax.php, which 'admin_init' does not. Quick
	 * Edit re-renders the row through wp_ajax_inline_save(), so registering
	 * there instead would drop the column from every inline save.
	 *
	 * @return void
	 */
	public function register_columns(): void {
		foreach ( Config::tracked_post_types() as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", [ $this, 'add_column' ] );
			add_action( "manage_{$post_type}_posts_custom_column", [ $this, 'render_column' ], 10, 2 );
			add_filter( "manage_edit-{$post_type}_sortable_columns", [ $this, 'sortable_column' ] );
		}
	}

	/**
	 * Add the freshness column after the title.
	 *
	 * @param array<string, string> $columns The existing columns.
	 * @return array<string, string>
	 */
	public function add_column( mixed $columns ): array {
		// See the note on handle_sorting(): this is a filter chain, so the
		// value is whatever the previous callback returned, not what core
		// passed in. A TypeError here fires during a list table render.
		if ( ! is_array( $columns ) ) {
			$columns = [];
		}

		$new_columns = [];
		$inserted    = false;

		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;

			if ( 'title' === $key ) {
				$new_columns[ self::COLUMN_ID ] = $this->column_heading();
				$inserted                       = true;
			}
		}

		// Not every post type has a title column, and a broken filter chain
		// may have handed us nothing at all. Append rather than disappear.
		if ( ! $inserted ) {
			$new_columns[ self::COLUMN_ID ] = $this->column_heading();
		}

		return $new_columns;
	}

	/**
	 * The column heading markup.
	 *
	 * @return string
	 */
	private function column_heading(): string {
		return '<span class="dashicons dashicons-clock" title="' . esc_attr__( 'Content Freshness', 'wpcity-content-freshness' ) . '"></span>';
	}

	/**
	 * Render the column content.
	 *
	 * @param string $column  The column name.
	 * @param int    $post_id The post ID.
	 * @return void
	 */
	public function render_column( string $column, int $post_id ): void {
		if ( 'wpcity_cf' !== $column ) {
			return;
		}

		$status = Freshness_Meta_Box::get_freshness_status( $post_id );

		if ( 'grey' === $status['color'] ) {
			echo '<span class="wpcity-cf-dot wpcity-cf-dot-grey" title="' . esc_attr( $status['label'] ) . '">—</span>';
			return;
		}

		printf(
			'<span class="wpcity-cf-dot wpcity-cf-dot-%s" title="%s">●</span>',
			esc_attr( $status['color'] ),
			esc_attr( $status['label'] )
		);
	}

	/**
	 * Make the column sortable.
	 *
	 * @param array<string, string> $columns Sortable columns.
	 * @return array<string, string>
	 */
	public function sortable_column( mixed $columns ): array {
		if ( ! is_array( $columns ) ) {
			$columns = [];
		}

		$columns[ self::COLUMN_ID ] = self::META_LAST_REVIEWED;

		return $columns;
	}

	/**
	 * Handle sorting by last reviewed date.
	 *
	 * @param \WP_Query $query The query.
	 * @return void
	 */
	public function handle_sorting( \WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( self::META_LAST_REVIEWED !== $query->get( 'orderby' ) ) {
			return;
		}

		/*
		 * A bare meta_key produces an INNER JOIN on postmeta, so every post
		 * that was never explicitly reviewed disappears from the list as soon
		 * as the user clicks the column header. Pairing EXISTS with NOT EXISTS
		 * makes it a LEFT JOIN and keeps those posts in the result.
		 */
		$clauses = [
			'relation' => 'OR',
			'reviewed' => [
				'key'     => self::META_LAST_REVIEWED,
				'compare' => 'EXISTS',
			],
			'never_reviewed' => [
				'key'     => self::META_LAST_REVIEWED,
				'compare' => 'NOT EXISTS',
			],
		];

		// Merge rather than overwrite; another plugin may already have set one.
		$existing = $query->get( 'meta_query' );

		$query->set(
			'meta_query',
			empty( $existing ) ? $clauses : [ 'relation' => 'AND', $existing, $clauses ]
		);

		$query->set( 'orderby', [ 'reviewed' => 'ASC' === strtoupper( (string) $query->get( 'order' ) ) ? 'ASC' : 'DESC' ] );
	}

	/**
	 * Enqueue admin styles on list screens.
	 *
	 * @return void
	 */
	public function enqueue_styles(): void {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}

		$allowed_screens = array_map( fn( string $pt ): string => "edit-{$pt}", Config::tracked_post_types() );

		if ( ! in_array( $screen->id, $allowed_screens, true ) ) {
			return;
		}

		wp_enqueue_style(
			'wpcity-cf-admin',
			WPCITY_CF_URL . 'admin/css/freshness-admin.css',
			[],
			WPCITY_CF_VERSION
		);
	}
}
