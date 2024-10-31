<?php

namespace RP\AdsManager\WP;

use RP\AdsManager\Controller\Backend;

final class Menu {
	const DEFAULT_PAGE = 'dashboard';

	private static $pages = [
		'dashboard'  => 'Dashboard',
		'codes'      => 'Codes',
		'groups'     => 'Groups',
		'shortcodes' => 'Shortcodes',
		'settings'   => 'Settings'
	];

	/**
	 * @return array
	 */
	public static function getPages() {
		return self::$pages;
	}

	public static function build() {
		$backend = Backend::instance();

		add_menu_page(
			'Ads Manager',
			'Ads Manager',
			'manage_options',
			sprintf( 'rpam-%s', Menu::DEFAULT_PAGE ),
			'',
			'dashicons-welcome-widgets-menus',
			'99.00001'
		);

		foreach ( self::$pages as $slug => $title ) {
			$page_hook = add_submenu_page(
				sprintf( 'rpam-%s', Menu::DEFAULT_PAGE ),
				__( $title, 'rp-ads-manager' ) . ' | RedPic Ads Manager',
				__( $title, 'rp-ads-manager' ),
				'manage_options',
				sprintf( 'rpam-%s', $slug ),
				function () use ( $backend, $slug ) {
					$backend->execute( $slug );
				}
			);
			add_action( 'load-' . $page_hook, [ Help::class, 'build' ] );
		}
	}
}
