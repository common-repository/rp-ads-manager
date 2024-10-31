<?php

/*
Plugin Name:       RedPic ADS Manager
Plugin URI:        https://wordpress.org/plugins/rp-ads-manager/
Description:       JS/HTML ads block manager. Allows you to create and insert blocks of code anywhere on the blog.
Version:           1.6.1
Author:            RedPic
Author URI:        https://profiles.wordpress.org/sadikoff
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       rp-ads-manager
Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'RPAM_PATH', __DIR__);
define( 'RPAM_VERSION', '1.6.1' );
define( 'RPAM_ASSETS', plugin_dir_url( __FILE__ ) . '/assets' );

require_once RPAM_PATH . '/autoloader.php';

/**
 * @param string $page
 * @param string $params
 *
 * @return string
 */
function rpam_url( $page, $params = '' ) {
	return admin_url( sprintf( 'admin.php?page=rpam-%s%s', $page, $params ? '&' . $params : '' ) );
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function a5a1ec9f() {
	loader_a5a1ec9f::instance()
	               ->register( RPAM_PATH )
	               ->hooks();

	add_action( 'plugins_loaded', [ loader_a5a1ec9f::instance(), 'launch' ], 999 );
}

a5a1ec9f();
