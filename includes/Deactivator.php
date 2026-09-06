<?php
/**
 * Plugin deactivator.
 *
 * @package WPCity\ContentFreshness
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness;

defined( 'ABSPATH' ) || exit;

/**
 * Handles plugin deactivation.
 *
 * @since 1.0.0
 */
class Deactivator {

	/**
	 * Run deactivation tasks.
	 *
	 * @return void
	 */
	public static function deactivate(): void {
		// The free plugin schedules no cron events and stores no transients.
		// Pro cleans up its own, see its Deactivator.

		/**
		 * Fires after the plugin has been deactivated.
		 *
		 * @since 1.0.0
		 */
		do_action( 'wpcity_cf_deactivated' );
	}
}
