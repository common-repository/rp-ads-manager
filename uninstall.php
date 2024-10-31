<?php

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rpam_codes" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rpam_groups" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rpam_options" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rpam_shortcodes" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rpam_statistics" );
delete_option( 'rpam_db_version' );