<?php
/**
 * Plugin activation handler.
 *
 * @package WPCity\ContentFreshness
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness;

defined( 'ABSPATH' ) || exit;

use WPCity\PluginBase\Core\Activator as Base_Activator;

/**
 * Plugin activation handler.
 *
 * @since 1.0.0
 */
class Activator extends Base_Activator {

	/**
	 * Run activation logic.
	 *
	 * @return void
	 */
	public static function activate(): void {
		// add_option() is a no-op when the option already exists, so a
		// reactivation never overwrites what the user configured.
		add_option( 'wpcity_cf_post_types', [ 'post', 'page' ] );
		add_option( 'wpcity_cf_default_interval', 180 );

		/**
		 * Fires after the plugin has been activated.
		 *
		 * @since 1.0.0
		 */
		do_action( 'wpcity_cf_activated' );
	}
}
