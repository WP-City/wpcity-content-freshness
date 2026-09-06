<?php
/**
 * Freshness meta box on post edit screen.
 *
 * @package WPCity\ContentFreshness\Admin
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness\Admin;

defined( 'ABSPATH' ) || exit;

use WP_Post;
use WPCity\ContentFreshness\Config;
use WPCity\PluginBase\Admin\Meta_Box;

/**
 * Sidebar meta box for managing review interval and marking content as reviewed.
 *
 * @since 1.0.0
 */
class Freshness_Meta_Box extends Meta_Box {

	private const BOX_ID   = 'wpcity_cf';
	private const CONTEXT  = 'side';
	private const PRIORITY = 'default';

	private const META_INTERVAL      = 'wpcity_cf_review_interval';
	private const META_LAST_REVIEWED = 'wpcity_cf_last_reviewed';

	/**
	 * Constructor.
	 *
	 * The title and the post types are deliberately left empty here and resolved
	 * in register_meta_box() instead.
	 *
	 * Abstract_Plugin constructs every ingredient on 'plugins_loaded', which is
	 * before 'after_setup_theme'. Calling __() at that point translates too
	 * early and makes WordPress log a _load_textdomain_just_in_time notice on
	 * every single request.
	 *
	 * The post types filter has the same problem for a different reason: at
	 * construction time no ingredient has run init() yet, and the Pro add-on
	 * only registers on 'plugins_loaded' at priority 20, so a filter applied
	 * here cannot see the callbacks that are about to be added.
	 */
	public function __construct() {
		parent::__construct(
			self::BOX_ID,
			'',
			[],
			self::CONTEXT,
			self::PRIORITY
		);
	}

	/**
	 * Initialize the ingredient.
	 *
	 * Hooks belong here rather than in the constructor, so that constructing
	 * the ingredient has no side effects.
	 *
	 * @return void
	 */
	public function init(): void {
		parent::init();

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		add_action( 'wp_ajax_wpcity_cf_mark_reviewed', [ $this, 'ajax_mark_reviewed' ] );
	}

	/**
	 * Register the meta box.
	 *
	 * Runs on 'add_meta_boxes', by which point the text domain is loaded and
	 * every plugin has had the chance to hook the filters below.
	 *
	 * Meta_Box also accepts callables for the title and the screens, which does
	 * the same thing in fewer lines. This override stays because it works
	 * against both that signature and the older string-only one. Every WPCity
	 * plugin ships its own copy of plugin-base under the same namespace, and on
	 * a site running a mix of versions whichever copy loads first defines the
	 * class for everyone. Passing a callable to an older copy is a fatal error
	 * that takes the whole site down, not just this plugin.
	 *
	 * @return void
	 */
	public function register_meta_box(): void {
		$post_types = Config::tracked_post_types();

		if ( empty( $post_types ) ) {
			return;
		}

		/**
		 * Filters the meta box title.
		 *
		 * @since 1.0.0
		 *
		 * @param string $title The meta box title.
		 */
		$default_title = __( 'Content Freshness', 'wpcity-content-freshness' );

		$title = apply_filters( 'wpcity_cf_metabox_label', $default_title );

		// Casting would turn null into an empty heading and an array into the
		// word "Array" plus a warning. Fall back to what went in instead.
		if ( ! is_string( $title ) || '' === $title ) {
			$title = $default_title;
		}

		add_meta_box(
			self::BOX_ID,
			$title,
			[ $this, 'render' ],
			$post_types,
			self::CONTEXT,
			self::PRIORITY
		);
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

		if ( ! in_array( $screen->post_type, Config::tracked_post_types(), true ) ) {
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
		$default       = Config::default_interval();

		// Fallback: use post modified date if never explicitly reviewed.
		if ( empty( $last_reviewed ) ) {
			$last_reviewed = get_the_modified_date( 'Y-m-d H:i:s', $post );
		}

		$status = self::get_freshness_status( $post->ID );

		?>
		<div class="wpcity-cf-container">

			<div class="wpcity-cf-field">
				<label for="wpcity-cf-interval"><strong><?php esc_html_e( 'Review Interval', 'wpcity-content-freshness' ); ?></strong></label>
				<select name="wpcity_cf_review_interval" id="wpcity-cf-interval">
					<option value="0" <?php selected( $interval, 0 ); ?>>
						<?php
						printf(
							/* translators: %d: default review interval in days. */
							esc_html__( 'Use default (%d days)', 'wpcity-content-freshness' ),
							$default
						);
						?>
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

		if ( ! isset( $_POST['wpcity_cf_review_interval'] ) ) {
			return;
		}

		$interval = (int) $_POST['wpcity_cf_review_interval'];

		/*
		 * -1 means "do not track" and 0 means "use the site default"; anything
		 * else is a length in days. The value goes straight into date
		 * arithmetic, so reject what the dropdown cannot produce instead of
		 * storing it.
		 */

		/**
		 * Filters the review intervals a post may be set to, in days.
		 *
		 * @since 1.0.0
		 *
		 * @param array<int, int> $allowed Allowed values. -1 disables tracking, 0 uses the site default.
		 */
		$default_allowed = [ -1, 0, 90, 180, 365 ];

		$allowed = apply_filters( 'wpcity_cf_allowed_intervals', $default_allowed );

		/*
		 * This is a write path, and discarding a broken filter result here
		 * discards the user's choice, not a third party's mistake. A callback
		 * that returns null would leave (array) null === [], every value would
		 * fail the check below, and the interval the user picked would be
		 * dropped on every save with nothing to show for it. So fall back to
		 * the list that went into the filter.
		 */
		$allowed = is_array( $allowed ) ? array_map( 'intval', $allowed ) : [];

		if ( [] === $allowed ) {
			$allowed = $default_allowed;
		}

		if ( ! in_array( $interval, $allowed, true ) ) {
			return;
		}

		update_post_meta( $post_id, self::META_INTERVAL, $interval );
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
			wp_send_json_error( __( 'Permission denied.', 'wpcity-content-freshness' ), 403 );
		}

		/*
		 * Allow Pro to gate this via a custom capability.
		 *
		 * The one filter result this plugin does not restore to its input. A
		 * permission answer is not the user's data, it is a decision, and a
		 * callback that returns nothing must not be read as a yes. This fails
		 * closed on purpose. The built-in edit_post check above already ran, so
		 * this only ever narrows access, never widens it.
		 */
		if ( ! (bool) apply_filters( 'wpcity_cf_can_mark_reviewed', true, $post_id ) ) {
			wp_send_json_error( __( 'You do not have permission to mark this as reviewed.', 'wpcity-content-freshness' ), 403 );
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
		$default       = Config::default_interval();

		// Not tracked.
		if ( -1 === $interval ) {
			return [ 'color' => 'grey', 'label' => __( 'Not tracked', 'wpcity-content-freshness' ), 'days' => 0 ];
		}

		$effective = $interval > 0 ? $interval : $default;

		// Never explicitly reviewed — use post's last modified date as baseline.
		if ( empty( $last_reviewed ) ) {
			$last_reviewed = get_the_modified_date( 'Y-m-d H:i:s', $post_id );
		}

		$reviewed_ts = strtotime( $last_reviewed );
		$deadline_ts = $reviewed_ts + ( $effective * DAY_IN_SECONDS );
		$now         = time();
		$days_left   = (int) ceil( ( $deadline_ts - $now ) / DAY_IN_SECONDS );

		$verdict  = $days_left <= 0;
		$is_stale = apply_filters( 'wpcity_cf_is_stale', $verdict, $post_id, $effective, $last_reviewed );

		// A callback that forgets to return yields null, and casting that to
		// false would report an overdue post as fresh. Keep our own verdict.
		if ( ! is_bool( $is_stale ) ) {
			$is_stale = $verdict;
		}

		if ( $is_stale ) {
			return [
				'color' => 'red',
				/* translators: %d: number of days the review is overdue. */
				'label' => sprintf( __( 'Overdue by %d days', 'wpcity-content-freshness' ), abs( $days_left ) ),
				'days'  => $days_left,
			];
		}

		if ( $days_left <= 30 ) {
			return [
				'color' => 'orange',
				/* translators: %d: number of days until the review is due. */
				'label' => sprintf( __( 'Due in %d days', 'wpcity-content-freshness' ), $days_left ),
				'days'  => $days_left,
			];
		}

		return [
			'color' => 'green',
			/* translators: %s: formatted date of the last review. */
			'label' => sprintf( __( 'Reviewed %s', 'wpcity-content-freshness' ), wp_date( get_option( 'date_format' ), $reviewed_ts ) ),
			'days'  => $days_left,
		];
	}
}
