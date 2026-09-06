<?php
/**
 * Uninstall handler.
 *
 * Removes everything this plugin owns: its options, its post meta, its user
 * meta, its transients, its scheduled events and the review capability.
 *
 * Scope note. Everything under the `wpcity_cf_` prefix is cleaned, not only the
 * keys the free plugin writes itself. The Pro add-on stores its own settings
 * inside that same prefix (`wpcity_cf_pro_*`), it cannot run without this
 * plugin, and it ships no uninstall.php of its own, so deleting this plugin is
 * the only moment that data is ever cleaned up.
 *
 * Deliberately NOT touched: `wpcity_content_freshness_pro_license_key` and
 * `_license_data`. Those sit outside this prefix, they are a paid credential
 * belonging to a plugin that may still be installed, and dropping the key here
 * would strand the activation on the licence server instead of releasing the
 * domain through License_Manager::deactivate_plugin(). That cleanup belongs in
 * the Pro plugin.
 *
 * @package WPCity\ContentFreshness
 */

declare( strict_types=1 );

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

const WPCITY_CF_UNINSTALL_PREFIX = 'wpcity_cf_';

/**
 * Delete every option whose name starts with the plugin prefix.
 *
 * Transients live in the options table as two rows: `_transient_{name}` holds
 * the value and `_transient_timeout_{name}` holds the expiry. Matching only the
 * first pattern leaves half of every cache entry behind, so both are collected.
 *
 * delete_option() is used rather than a bulk DELETE because it also clears the
 * alloptions and notoptions caches.
 *
 * @return void
 */
function wpcity_cf_uninstall_options(): void {
	global $wpdb;

	$like = $wpdb->esc_like( WPCITY_CF_UNINSTALL_PREFIX ) . '%';

	$names = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT option_name FROM {$wpdb->options}
			 WHERE option_name LIKE %s
			    OR option_name LIKE %s
			    OR option_name LIKE %s",
			$like,
			'_transient_' . $like,
			'_transient_timeout_' . $like
		)
	);

	foreach ( $names as $name ) {
		delete_option( $name );
	}
}

/**
 * Delete every post meta and user meta row under the plugin prefix.
 *
 * delete_metadata() with $delete_all = true removes the key for every object
 * and invalidates the meta caches, which a raw DELETE would not do.
 *
 * @return void
 */
function wpcity_cf_uninstall_meta(): void {
	global $wpdb;

	$like = $wpdb->esc_like( WPCITY_CF_UNINSTALL_PREFIX ) . '%';

	$tables = [
		'post' => $wpdb->postmeta,
		'user' => $wpdb->usermeta,
	];

	foreach ( $tables as $type => $table ) {
		$keys = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT meta_key FROM {$table} WHERE meta_key LIKE %s",
				$like
			)
		);

		foreach ( $keys as $key ) {
			delete_metadata( $type, 0, $key, '', true );
		}
	}
}

/**
 * Unschedule every cron event in the plugin namespace.
 *
 * Scheduled events live inside the `cron` option as a nested array, so the
 * option sweep above never sees them.
 *
 * @return void
 */
function wpcity_cf_uninstall_cron(): void {
	$cron = _get_cron_array();

	if ( ! is_array( $cron ) ) {
		return;
	}

	$hooks = [];

	foreach ( $cron as $events ) {
		foreach ( array_keys( (array) $events ) as $hook ) {
			if ( str_starts_with( (string) $hook, WPCITY_CF_UNINSTALL_PREFIX ) ) {
				$hooks[ $hook ] = true;
			}
		}
	}

	foreach ( array_keys( $hooks ) as $hook ) {
		wp_clear_scheduled_hook( $hook );
	}
}

/**
 * Remove the review capability from every role that has it.
 *
 * @return void
 */
function wpcity_cf_uninstall_capability(): void {
	$roles = wp_roles();

	foreach ( array_keys( $roles->roles ) as $slug ) {
		$role = $roles->get_role( $slug );

		if ( $role instanceof WP_Role ) {
			$role->remove_cap( 'wpcity_cf_mark_reviewed' );
		}
	}
}

/**
 * Remove every trace of the plugin from a single site.
 *
 * @return void
 */
function wpcity_cf_uninstall_site(): void {
	wpcity_cf_uninstall_cron();
	wpcity_cf_uninstall_meta();
	wpcity_cf_uninstall_options();
	wpcity_cf_uninstall_capability();
}

if ( is_multisite() ) {
	$wpcity_cf_site_ids = get_sites(
		[
			'fields'                 => 'ids',
			'number'                 => 0,
			'update_site_meta_cache' => false,
		]
	);

	foreach ( $wpcity_cf_site_ids as $wpcity_cf_site_id ) {
		switch_to_blog( (int) $wpcity_cf_site_id );
		wpcity_cf_uninstall_site();
		restore_current_blog();
	}

	unset( $wpcity_cf_site_ids, $wpcity_cf_site_id );
} else {
	wpcity_cf_uninstall_site();
}
