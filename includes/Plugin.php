<?php
/**
 * Main plugin class.
 *
 * @package WPCity\ContentFreshness
 */

declare( strict_types=1 );

namespace WPCity\ContentFreshness;

defined( 'ABSPATH' ) || exit;

use WPCity\PluginBase\Abstract_Plugin;
use WPCity\PluginBase\Core\I18n;
use WPCity\ContentFreshness\Admin\Settings_Page;
use WPCity\ContentFreshness\Admin\Freshness_Meta_Box;
use WPCity\ContentFreshness\Admin\Freshness_Column;
use WPCity\ContentFreshness\Admin\Dashboard_Widget;

/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
class Plugin extends Abstract_Plugin {

	/**
	 * Run the plugin.
	 *
	 * @return void
	 */
	public function run(): void {
		$this->register( new I18n( 'wpcity-content-freshness', '/languages' ) );
		$this->register( new Settings_Page() );
		$this->register( new Freshness_Meta_Box() );
		$this->register( new Freshness_Column() );
		$this->register( new Dashboard_Widget() );

		parent::run();
	}

	/**
	 * Get the hook prefix for this plugin.
	 *
	 * @return string
	 */
	protected function get_hook_prefix(): string {
		return 'wpcity_cf';
	}
}
