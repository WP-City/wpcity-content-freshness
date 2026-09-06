<?php
/**
 * Dashboard widget showing stale content.
 *
 * @package WPCity\ContentFreshness\Admin
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness\Admin;

defined( 'ABSPATH' ) || exit;

use WPCity\PluginBase\Ingredient_Interface;

/**
 * WordPress dashboard widget: top stale posts.
 *
 * @since 1.0.0
 */
class Dashboard_Widget implements Ingredient_Interface {

	/**
	 * Initialize the ingredient.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'wp_dashboard_setup', [ $this, 'register_widget' ] );
	}

	/**
	 * Register the dashboard widget.
	 *
	 * @return void
	 */
	public function register_widget(): void {
		wp_add_dashboard_widget(
			'wpcity_cf_dashboard',
			__( 'Content Freshness', 'wpcity-content-freshness' ),
			[ $this, 'render_widget' ]
		);
	}

	/**
	 * Render the widget content.
	 *
	 * @return void
	 */
	public function render_widget(): void {
		$post_types = apply_filters( 'wpcity_cf_post_types', [ 'post', 'page' ] );
		$default    = (int) apply_filters( 'wpcity_cf_default_interval', 180 );

		// Query posts that have been reviewed and might be stale.
		$posts = get_posts( [
			'post_type'      => $post_types,
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'meta_query'     => [
				'relation' => 'OR',
				[
					'key'     => 'wpcity_cf_last_reviewed',
					'compare' => 'EXISTS',
				],
				[
					'key'     => 'wpcity_cf_review_interval',
					'value'   => '-1',
					'compare' => '!=',
				],
			],
			'fields' => 'ids',
		] );

		// Also include posts without any meta (they use default interval and were never reviewed).
		$never_reviewed = get_posts( [
			'post_type'      => $post_types,
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'meta_query'     => [
				[
					'key'     => 'wpcity_cf_last_reviewed',
					'compare' => 'NOT EXISTS',
				],
				[
					'relation' => 'OR',
					[
						'key'     => 'wpcity_cf_review_interval',
						'value'   => '-1',
						'compare' => '!=',
					],
					[
						'key'     => 'wpcity_cf_review_interval',
						'compare' => 'NOT EXISTS',
					],
				],
			],
			'fields' => 'ids',
		] );

		$all_ids = array_unique( array_merge( $posts, $never_reviewed ) );

		// Filter to only stale posts and sort by urgency.
		$stale = [];
		foreach ( $all_ids as $pid ) {
			$status = Freshness_Meta_Box::get_freshness_status( $pid );
			if ( 'red' === $status['color'] || 'orange' === $status['color'] ) {
				$stale[] = [
					'id'     => $pid,
					'title'  => get_the_title( $pid ),
					'status' => $status,
				];
			}
		}

		// Sort: most overdue first.
		usort( $stale, fn( $a, $b ) => $a['status']['days'] <=> $b['status']['days'] );

		$count = count( $stale );
		$top5  = array_slice( $stale, 0, 5 );

		?>
		<div class="wpcity-cf-dashboard">
			<?php if ( 0 === $count ) : ?>
				<p class="wpcity-cf-dashboard-ok">
					<?php esc_html_e( 'All content is up to date.', 'wpcity-content-freshness' ); ?>
				</p>
			<?php else : ?>
				<p class="wpcity-cf-dashboard-count">
					<?php
					printf(
						esc_html( _n( '%d post needs review', '%d posts need review', $count, 'wpcity-content-freshness' ) ),
						$count
					);
					?>
				</p>
				<ul class="wpcity-cf-dashboard-list">
					<?php foreach ( $top5 as $item ) : ?>
						<li>
							<span class="wpcity-cf-dot wpcity-cf-dot-<?php echo esc_attr( $item['status']['color'] ); ?>">●</span>
							<a href="<?php echo esc_url( get_edit_post_link( $item['id'] ) ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
							<span class="wpcity-cf-dashboard-meta"><?php echo esc_html( $item['status']['label'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php if ( $count > 5 ) : ?>
					<p class="wpcity-cf-dashboard-more">
						<a href="<?php echo esc_url( admin_url( 'edit.php?orderby=wpcity_cf_last_reviewed&order=asc' ) ); ?>">
							<?php printf( esc_html__( 'View all %d posts', 'wpcity-content-freshness' ), $count ); ?>
						</a>
					</p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
	}
}
