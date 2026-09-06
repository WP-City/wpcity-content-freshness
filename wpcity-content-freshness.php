<?php
/**
 * WPCity Content Freshness
 *
 * @package WPCity\ContentFreshness
 * @wordpress-plugin
 * Plugin Name: WPCity Content Freshness
 * Plugin URI: https://wpcity.dev/plugins/content-freshness
 * Description: Track when your content was last reviewed and get notified when it needs updating.
 * Version: 1.0.0
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Author: WPCity
 * Author URI: https://wpcity.dev
 * License: GPL v2 or later
 * Text Domain: wpcity-content-freshness
 * Domain Path: /languages
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WPCITY_CF_VERSION' ) ) {
	define( 'WPCITY_CF_VERSION', '1.0.0' );
}
if ( ! defined( 'WPCITY_CF_FILE' ) ) {
	define( 'WPCITY_CF_FILE', __FILE__ );
}
if ( ! defined( 'WPCITY_CF_PATH' ) ) {
	define( 'WPCITY_CF_PATH', __DIR__ );
}
if ( ! defined( 'WPCITY_CF_URL' ) ) {
	define( 'WPCITY_CF_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WPCITY_CF_BASENAME' ) ) {
	define( 'WPCITY_CF_BASENAME', plugin_basename( __FILE__ ) );
}

require_once __DIR__ . '/vendor/autoload.php';

use WPCity\ContentFreshness\Plugin;
use WPCity\ContentFreshness\Activator;
use WPCity\ContentFreshness\Deactivator;

register_activation_hook( WPCITY_CF_FILE, [ Activator::class, 'activate' ] );
register_deactivation_hook( WPCITY_CF_FILE, [ Deactivator::class, 'deactivate' ] );

add_action( 'plugins_loaded', function() {
	$plugin = new Plugin();
	$plugin->run();
} );
