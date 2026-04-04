<?php
/**
 * Freshness meta box on post edit screen.
 *
 * @package WPCity\ContentFreshness\Admin
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness\Admin;

use WP_Post;
use WPCity\PluginBase\Admin\Meta_Box;

/**
 * Sidebar meta box for managing review interval and marking content as reviewed.
 *
 * @since 1.0.0
 */
class Freshness_Meta_Box extends Meta_Box {

	private const META_INTERVAL      = 'wpcity_cf_review_interval';
	private const META_LAST_REVIEWED = 'wpcity_cf_last_reviewed';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$post_types = apply_filters( 'wpcity_cf_post_types', [ 'post', 'page' ] );
		$title      = apply_filters( 'wpcity_cf_metabox_label', __( 'Content Freshness', 'wpcity-content-freshness' ) );

		parent::__construct(
			'wpcity_cf',
			$title,
			$post_types,
			'side',
			'high'
		);

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'wp_ajax_wpcity_cf_mark_reviewed', [ $this, 'ajax_mark_reviewed' ] );
	}

	/**
	 * Enqueue meta box assets.
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		$screen = get_current_screen();
		if ( ! $screen || 'post' !== $screen->base ) {
			return;
		}

		$allowed = apply_filters( 'wpcity_cf_post_types', [ 'post', 'page' ] );
		if ( ! in_array( $screen->post_type, $allowed, true ) ) {
			return;
		}

		wp_enqueue_style(
			'wpcity-cf-admin',
			WPCITY_CF_URL . 'admin/css/freshness-admin.css',
			[],
			WPCITY_CF_VERSION
		);

		wp_enqueue_script(
			'wpcity-cf-admin',
			WPCITY_CF_URL . 'admin/js/freshness-admin.js',
			[],
			WPCITY_CF_VERSION,
			true
		);

		wp_localize_script( 'wpcity-cf-admin', 'wpcityCF', [
			'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
			'nonce'          => wp_create_nonce( 'wpcity_cf_ajax' ),
			'markingText'    => __( 'Saving...', 'wpcity-content-freshness' ),
			'reviewedText'   => __( 'Reviewed just now', 'wpcity-content-freshness' ),
			'markedText'     => __( 'Mark as Reviewed', 'wpcity-content-freshness' ),
		] );
	}

	/**
	 * Render the meta box content.
	 *
	 * @param WP_Post $post The post object.
	 * @return void
	 */
	public function render( WP_Post $post ): void {
		wp_nonce_field( 'wpcity_cf_nonce', 'wpcity_cf_nonce_field' );

		$interval      = (int) get_post_meta( $post->ID, self::META_INTERVAL, true );
		$last_reviewed = get_post_meta( $post->ID, self::META_LAST_REVIEWED, true );
		$default       = (int) apply_filters( 'wpcity_cf_default_interval', 180 );

		$effective_interval = $interval > 0 ? $interval : ( -1 === $interval ? 0 : $default );
		$status             = self::get_freshness_status( $post->ID );

		?>
		<div class="wpcity-cf-container">

			<div class="wpcity-cf-field">
				<label for="wpcity-cf-interval"><strong><?php esc_html_e( 'Review Interval', 'wpcity-content-freshness' ); ?></strong></label>
				<select name="wpcity_cf_review_interval" id="wpcity-cf-interval">
					<option value="0" <?php selected( $interval, 0 ); ?>>
						<?php printf( esc_html__( 'Use default (%d days)', 'wpcity-content-freshness' ), $default ); ?>
					</option>
					<option value="90" <?php selected( $interval, 90 ); ?>><?php esc_html_e( '3 months', 'wpcity-content-freshness' ); ?></option>
					<option value="180" <?php selected( $interval, 180 ); ?>><?php esc_html_e( '6 months', 'wpcity-content-freshness' ); ?></option>
					<option value="365" <?php selected( $interval, 365 ); ?>><?php esc_html_e( '12 months', 'wpcity-content-freshness' ); ?></option>
					<option value="-1" <?php selected( $interval, -1 ); ?>><?php esc_html_e( "Don't track", 'wpcity-content-freshness' ); ?></option>
				</select>
			</div>

			<div class="wpcity-cf-status" id="wpcity-cf-status">
				<?php if ( $last_reviewed ) : ?>
					<p class="wpcity-cf-last-reviewed">
						<strong><?php esc_html_e( 'Last Reviewed:', 'wpcity-content-freshness' ); ?></strong>
						<?php echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $last_reviewed ) ) ); ?>
					</p>
				<?php endif; ?>

				<?php if ( -1 !== $interval ) : ?>
					<p class="wpcity-cf-indicator wpcity-cf-indicator-<?php echo esc_attr( $status['color'] ); ?>">
						<span class="wpcity-cf-dot"></span>
						<?php echo esc_html( $status['label'] ); ?>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( -1 !== $interval ) : ?>
				<button
					type="button"
					class="button wpcity-cf-mark-reviewed"
					data-post-id="<?php echo (int) $post->ID; ?>"
				>
					<?php esc_html_e( 'Mark as Reviewed', 'wpcity-content-freshness' ); ?>
				</button>
			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Save the meta box data (on post save).
	 *
	 * @param int $post_id The post ID.
	 * @return void
	 */
	public function save( int $post_id ): void {
		if ( ! isset( $_POST['wpcity_cf_nonce_field'] ) ||
			 ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpcity_cf_nonce_field'] ) ), 'wpcity_cf_nonce' ) ) {
			return;
		}

		if ( isset( $_POST['wpcity_cf_review_interval'] ) ) {
			$interval = (int) $_POST['wpcity_cf_review_interval'];
			update_post_meta( $post_id, self::META_INTERVAL, $interval );
		}
	}

	/**
	 * AJAX handler: mark a post as reviewed.
	 *
	 * @return void
	 */
	public function ajax_mark_reviewed(): void {
		check_ajax_referer( 'wpcity_cf_ajax', 'nonce' );

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( 'Permission denied.', 403 );
		}

		// Allow Pro to gate this via custom capability.
		if ( ! apply_filters( 'wpcity_cf_can_mark_reviewed', true, $post_id ) ) {
			wp_send_json_error( 'You do not have permission to mark this as reviewed.', 403 );
		}

		$now = current_time( 'mysql' );
		update_post_meta( $post_id, self::META_LAST_REVIEWED, $now );

		do_action( 'wpcity_cf_marked_reviewed', $post_id, get_current_user_id() );

		$status = self::get_freshness_status( $post_id );

		wp_send_json_success( [
			'last_reviewed' => wp_date( get_option( 'date_format' ), strtotime( $now ) ),
			'status'        => $status,
		] );
	}

	/**
	 * Get the freshness status for a post.
	 *
	 * @param int $post_id The post ID.
	 * @return array{color: string, label: string, days: int}
	 */
	public static function get_freshness_status( int $post_id ): array {
		$interval      = (int) get_post_meta( $post_id, self::META_INTERVAL, true );
		$last_reviewed = get_post_meta( $post_id, self::META_LAST_REVIEWED, true );
		$default       = (int) apply_filters( 'wpcity_cf_default_interval', 180 );

		// Not tracked.
		if ( -1 === $interval ) {
			return [ 'color' => 'grey', 'label' => __( 'Not tracked', 'wpcity-content-freshness' ), 'days' => 0 ];
		}

		$effective = $interval > 0 ? $interval : $default;

		// Never reviewed.
		if ( empty( $last_reviewed ) ) {
			return [ 'color' => 'red', 'label' => __( 'Never reviewed', 'wpcity-content-freshness' ), 'days' => 999 ];
		}

		$reviewed_ts = strtotime( $last_reviewed );
		$deadline_ts = $reviewed_ts + ( $effective * DAY_IN_SECONDS );
		$now         = time();
		$days_left   = (int) ceil( ( $deadline_ts - $now ) / DAY_IN_SECONDS );

		$is_stale = apply_filters( 'wpcity_cf_is_stale', $days_left <= 0, $post_id, $effective, $last_reviewed );

		if ( $is_stale ) {
			return [
				'color' => 'red',
				'label' => sprintf( __( 'Overdue by %d days', 'wpcity-content-freshness' ), abs( $days_left ) ),
				'days'  => $days_left,
			];
		}

		if ( $days_left <= 30 ) {
			return [
				'color' => 'orange',
				'label' => sprintf( __( 'Due in %d days', 'wpcity-content-freshness' ), $days_left ),
				'days'  => $days_left,
			];
		}

		return [
			'color' => 'green',
			'label' => sprintf( __( 'Reviewed %s', 'wpcity-content-freshness' ), wp_date( get_option( 'date_format' ), $reviewed_ts ) ),
			'days'  => $days_left,
		];
	}
}
