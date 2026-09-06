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
		if ( false === get_option( 'wpcity_cf_post_types' ) ) {
			update_option( 'wpcity_cf_post_types', [ 'post', 'page' ] );
		}

		if ( false === get_option( 'wpcity_cf_default_interval' ) ) {
			update_option( 'wpcity_cf_default_interval', 180 );
		}
	}
}
