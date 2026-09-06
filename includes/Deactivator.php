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
		// Nothing to clean up. Pro handles its own cron cleanup.
	}
}
