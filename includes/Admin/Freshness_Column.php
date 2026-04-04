<?php
/**
 * Freshness indicator column in post list table.
 *
 * @package WPCity\ContentFreshness\Admin
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness\Admin;

use WPCity\PluginBase\Ingredient_Interface;

/**
 * Adds a column showing freshness status per post.
 *
 * @since 1.0.0
 */
class Freshness_Column implements Ingredient_Interface {

	/**
	 * Initialize the ingredient.
	 *
	 * @return void
	 */
	public function init(): void {
		$post_types = apply_filters( 'wpcity_cf_post_types', [ 'post', 'page' ] );

		foreach ( $post_types as $post_type ) {
			add_filter( "manage_{$post_type}_posts_columns", [ $this, 'add_column' ] );
			add_action( "manage_{$post_type}_posts_custom_column", [ $this, 'render_column' ], 10, 2 );
			add_filter( "manage_edit-{$post_type}_sortable_columns", [ $this, 'sortable_column' ] );
		}

		add_action( 'pre_get_posts', [ $this, 'handle_sorting' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
	}

	/**
	 * Add the freshness column after the title.
	 *
	 * @param array<string, string> $columns The existing columns.
	 * @return array<string, string>
	 */
	public function add_column( array $columns ): array {
		$new_columns = [];

		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;

			if ( 'title' === $key ) {
				$new_columns['wpcity_cf'] = '<span class="dashicons dashicons-clock" title="' . esc_attr__( 'Content Freshness', 'wpcity-content-freshness' ) . '"></span>';
			}
		}

		return $new_columns;
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
	public function sortable_column( array $columns ): array {
		$columns['wpcity_cf'] = 'wpcity_cf_last_reviewed';
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

		if ( 'wpcity_cf_last_reviewed' !== $query->get( 'orderby' ) ) {
			return;
		}

		$query->set( 'meta_key', 'wpcity_cf_last_reviewed' );
		$query->set( 'orderby', 'meta_value' );
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

		$allowed         = apply_filters( 'wpcity_cf_post_types', [ 'post', 'page' ] );
		$allowed_screens = array_map( fn( $pt ) => "edit-{$pt}", $allowed );

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
