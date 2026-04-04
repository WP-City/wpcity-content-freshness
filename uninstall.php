<?php
/**
 * Uninstall handler — removes all plugin data.
 *
 * @package WPCity\ContentFreshness
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete all post meta.
delete_post_meta_by_key( 'wpcity_cf_review_interval' );
delete_post_meta_by_key( 'wpcity_cf_last_reviewed' );
delete_post_meta_by_key( 'wpcity_cf_snooze_until' );
delete_post_meta_by_key( 'wpcity_cf_webhook_sent' );

// Delete options.
delete_option( 'wpcity_cf_post_types' );
delete_option( 'wpcity_cf_default_interval' );
