<?php
/**
 * Uninstall handler.
 *
 * Removes exactly what this plugin writes, by name. Two options and two post
 * meta keys; the free plugin schedules no cron events, stores no transients,
 * writes no user meta and grants no capabilities. Anything added later has to
 * be added to the lists below.
 *
 * Why a name list and not a `LIKE 'wpcity_cf_%'` sweep, which is shorter and
 * was what this file did until this commit. The Pro add-on writes its own
 * settings under `wpcity_cf_pro_`, which nests inside `wpcity_cf_`, so the
 * sweep silently reaches into a separate plugin. Measured on a fully
 * configured pair:
 *
 *     sweep LIKE 'wpcity_cf_%'  ->  13 option rows: 2 mine, 11 Pro
 *                                    4 post meta keys: 2 mine, 2 Pro
 *                                    plus Pro's cron event and Pro's
 *                                    wpcity_cf_mark_reviewed capability
 *
 * Pro removes every one of those itself, in its own uninstall.php since
 * ce359b8. The sweep is therefore redundant in the normal case and wrong in
 * the one that matters: removing the free plugin and reinstalling it is an
 * ordinary repair, and the Pro plugin survives it. Its dependency check runs
 * on 'plugins_loaded' at priority 20, shows an admin notice and returns; it
 * does not deactivate itself. Wiping its settings on the way past would be a
 * plugin deleting another plugin's live configuration.
 *
 * The licence key is the sharpest case and the reason the boundary is drawn at
 * ownership rather than at what a key is worth. Activations are capped per
 * licence, and a customer only gets one back by releasing it: Pro's own
 * uninstaller posts action=deactivate to the licence server before it deletes
 * the key. Anything that deletes that key without making the call, this file
 * included, costs the customer an activation slot and leaves them to free it
 * by hand through wpcity.dev.
 *
 * The rule, across all three WPCity freemium pairs: an uninstaller removes
 * only what its own plugin writes, and a prefix sweep is fine except where
 * another shipped plugin's keys nest under that prefix.
 *
 * @package WPCity\ContentFreshness
 */

declare( strict_types=1 );

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Remove every trace of this plugin from a single site.
 *
 * @return void
 */
function wpcity_cf_uninstall_site(): void {
	// Options written by Activator and the settings page.
	delete_option( 'wpcity_cf_post_types' );
	delete_option( 'wpcity_cf_default_interval' );

	/*
	 * Post meta written by the meta box. delete_post_meta_by_key() removes the
	 * key for every post and invalidates the meta cache, which a bulk DELETE
	 * would not.
	 *
	 * wpcity_cf_snooze_until and wpcity_cf_webhook_sent look like they belong
	 * here because they carry this plugin's prefix, but Pro writes them and
	 * Pro removes them.
	 */
	delete_post_meta_by_key( 'wpcity_cf_review_interval' );
	delete_post_meta_by_key( 'wpcity_cf_last_reviewed' );
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
