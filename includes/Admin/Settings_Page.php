<?php
/**
 * Plugin settings page.
 *
 * @package WPCity\ContentFreshness\Admin
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness\Admin;

use WPCity\PluginBase\Ingredient_Interface;

/**
 * Settings page with 2-column layout.
 *
 * @since 1.0.0
 */
class Settings_Page implements Ingredient_Interface {

	private const OPTION_POST_TYPES       = 'wpcity_cf_post_types';
	private const OPTION_DEFAULT_INTERVAL = 'wpcity_cf_default_interval';

	/**
	 * Initialize the ingredient.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_filter( 'wpcity_cf_post_types', [ $this, 'get_enabled_post_types' ] );
		add_filter( 'wpcity_cf_default_interval', [ $this, 'get_default_interval' ] );
	}

	/**
	 * Add settings page under Settings menu.
	 *
	 * @return void
	 */
	public function add_menu_page(): void {
		$hook = add_options_page(
			__( 'Content Freshness', 'wpcity-content-freshness' ),
			__( 'Content Freshness', 'wpcity-content-freshness' ),
			'manage_options',
			'wpcity-content-freshness',
			[ $this, 'render_page' ]
		);

		if ( $hook ) {
			add_action( "load-{$hook}", [ $this, 'enqueue_postbox' ] );
			add_action( "load-{$hook}", [ $this, 'set_footer_text' ] );
			add_action( "load-{$hook}", [ $this, 'register_header_hook' ] );
		}
	}

	public function enqueue_postbox(): void {
		wp_enqueue_script( 'postbox' );
	}

	public function register_header_hook(): void {
		add_action( 'in_admin_header', [ $this, 'render_header' ] );
	}

	public function render_header(): void {
		?>
		<div class="wpcity-settings-header">
			<img class="wpcity-settings-logo" src="<?php echo esc_url( WPCITY_CF_URL . 'admin/images/wpcity-logo.svg' ); ?>" alt="WPCity" />
		</div>
		<?php
	}

	public function set_footer_text(): void {
		add_filter( 'admin_footer_text', [ $this, 'render_footer_text' ] );
	}

	public function render_footer_text( string $text ): string {
		return sprintf(
			esc_html__( 'If you like %1$s, please leave us a %2$s rating. Thank you!', 'wpcity-content-freshness' ),
			'<strong>WPCity Content Freshness</strong>',
			'<a href="https://wordpress.org/support/view/plugin-reviews/wpcity-content-freshness?filter=5#postform" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
		);
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		register_setting( 'wpcity_cf_settings', self::OPTION_POST_TYPES, [
			'type'              => 'array',
			'sanitize_callback' => [ $this, 'sanitize_post_types' ],
			'default'           => [ 'post', 'page' ],
		] );

		register_setting( 'wpcity_cf_settings', self::OPTION_DEFAULT_INTERVAL, [
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'default'           => 180,
		] );
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function render_page(): void {
		?>
		<div class="wrap wpcity-settings-wrap">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">

					<div id="post-body-content">
						<form method="post" action="options.php">
							<?php settings_fields( 'wpcity_cf_settings' ); ?>

							<div class="meta-box-sortables">
								<div class="postbox">
									<h3><span><?php esc_html_e( 'Settings', 'wpcity-content-freshness' ); ?></span></h3>
									<div class="inside">
										<table class="form-table">
											<tbody>
												<tr valign="top">
													<th scope="row"><strong><?php esc_html_e( 'Post Types', 'wpcity-content-freshness' ); ?></strong></th>
													<td>
														<?php $this->render_post_types_field(); ?>
														<p class="description"><?php esc_html_e( 'Select which post types to track for content freshness.', 'wpcity-content-freshness' ); ?></p>
													</td>
												</tr>
												<tr valign="top">
													<th scope="row"><strong><?php esc_html_e( 'Default Review Interval', 'wpcity-content-freshness' ); ?></strong></th>
													<td>
														<?php $this->render_interval_field(); ?>
														<p class="description"><?php esc_html_e( 'Default interval for reviewing content. Can be overridden per post.', 'wpcity-content-freshness' ); ?></p>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>

								<?php do_action( 'wpcity_cf_settings_after_postboxes' ); ?>
							</div>

							<?php submit_button(); ?>
						</form>
					</div>

					<div id="postbox-container-1" class="postbox-container">
						<div class="meta-box-sortables">
							<div class="postbox">
								<h3><span><?php esc_html_e( 'Rate & Review', 'wpcity-content-freshness' ); ?></span></h3>
								<div class="inside">
									<p><?php printf( esc_html__( 'Enjoy Content Freshness? Please consider leaving a %s rating. Thank you!', 'wpcity-content-freshness' ), '<a href="https://wordpress.org/support/view/plugin-reviews/wpcity-content-freshness?filter=5#postform" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a>' ); ?></p>
									<p><a href="https://wordpress.org/support/view/plugin-reviews/wpcity-content-freshness?filter=5#postform" target="_blank" rel="noopener noreferrer" class="button button-primary"><?php esc_html_e( 'Leave a Review', 'wpcity-content-freshness' ); ?></a></p>
								</div>
							</div>
							<div class="postbox">
								<h3><span><?php esc_html_e( 'Need Help?', 'wpcity-content-freshness' ); ?></span></h3>
								<div class="inside">
									<p><?php esc_html_e( 'Having issues or need support? We are happy to help!', 'wpcity-content-freshness' ); ?></p>
									<p><a href="https://wordpress.org/support/plugin/wpcity-content-freshness/" target="_blank" rel="noopener noreferrer" class="button button-primary"><?php esc_html_e( 'Get Support', 'wpcity-content-freshness' ); ?></a></p>
								</div>
							</div>
							<div class="postbox">
								<h3><span><?php esc_html_e( 'About Content Freshness', 'wpcity-content-freshness' ); ?></span></h3>
								<div class="inside">
									<p><?php esc_html_e( 'Keep your content up to date by tracking when each page was last reviewed.', 'wpcity-content-freshness' ); ?></p>
									<ul>
										<li><?php esc_html_e( 'Review interval per post or page', 'wpcity-content-freshness' ); ?></li>
										<li><?php esc_html_e( 'Color-coded freshness indicator in post list', 'wpcity-content-freshness' ); ?></li>
										<li><?php esc_html_e( 'Dashboard widget with stale content', 'wpcity-content-freshness' ); ?></li>
										<li><?php esc_html_e( 'Mark as reviewed with one click', 'wpcity-content-freshness' ); ?></li>
									</ul>
									<p><?php printf( esc_html__( 'Version %s', 'wpcity-content-freshness' ), esc_html( WPCITY_CF_VERSION ) ); ?> | <a href="https://wpcity.dev" target="_blank" rel="noopener noreferrer">WPCity.dev</a></p>
								</div>
							</div>
						</div>
					</div>

				</div>
				<br class="clear">
			</div>
		</div>
		<?php
	}

	private function render_post_types_field(): void {
		$post_types = get_post_types( [ 'public' => true ], 'objects' );
		$enabled    = $this->get_enabled_post_types( [] );

		echo '<fieldset>';
		foreach ( $post_types as $post_type ) {
			if ( 'attachment' === $post_type->name ) {
				continue;
			}
			$checked = in_array( $post_type->name, $enabled, true ) ? 'checked' : '';
			printf(
				'<label style="display:block;margin-bottom:8px;"><input type="checkbox" name="%s[]" value="%s" %s /> %s</label>',
				esc_attr( self::OPTION_POST_TYPES ),
				esc_attr( $post_type->name ),
				esc_attr( $checked ),
				esc_html( $post_type->labels->name )
			);
		}
		echo '</fieldset>';
	}

	private function render_interval_field(): void {
		$value = (int) get_option( self::OPTION_DEFAULT_INTERVAL, 180 );
		?>
		<select name="<?php echo esc_attr( self::OPTION_DEFAULT_INTERVAL ); ?>">
			<option value="90" <?php selected( $value, 90 ); ?>><?php esc_html_e( '3 months (90 days)', 'wpcity-content-freshness' ); ?></option>
			<option value="180" <?php selected( $value, 180 ); ?>><?php esc_html_e( '6 months (180 days)', 'wpcity-content-freshness' ); ?></option>
			<option value="365" <?php selected( $value, 365 ); ?>><?php esc_html_e( '12 months (365 days)', 'wpcity-content-freshness' ); ?></option>
		</select>
		<?php
	}

	public function sanitize_post_types( mixed $value ): array {
		if ( ! is_array( $value ) ) {
			return [ 'post', 'page' ];
		}
		$valid = get_post_types( [ 'public' => true ] );
		return array_values( array_intersect( $value, $valid ) );
	}

	public function get_enabled_post_types( array $default ): array {
		$saved = get_option( self::OPTION_POST_TYPES );
		if ( ! is_array( $saved ) || empty( $saved ) ) {
			return [ 'post', 'page' ];
		}
		return $saved;
	}

	public function get_default_interval( int $default ): int {
		return (int) get_option( self::OPTION_DEFAULT_INTERVAL, 180 );
	}
}
