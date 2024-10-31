<?php

namespace RP\AdsManager\WP;

use RP\AdsManager\Util\Request;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
final class Help {
	public static function build() {
		$request = Request::instance();

		$helpMethod = 'build' . ucfirst( $request->page() );

		$screen = call_user_func( [ Help::class, $helpMethod ], get_current_screen() );

		// Help sidebars are optional
		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', 'rp-ads-manager' ) . '</strong></p>' .
			'<p><a href="https://redpic.org" target="_blank">' . __( 'Official page', 'rp-ads-manager' ) . '</a></p>' .
			'<p><a href="https://wordpress.org/support/plugin/rp-ads-manager" target="_blank">' . __( 'Support Forums', 'rp-ads-manager' ) . '</a></p>'
		);
	}

	/**
	 * @param \WP_Screen $screen
	 *
	 * @return \WP_Screen
	 */
	public static function buildDashboard( $screen ) {
		$screen->add_help_tab( [
			'id'      => 'overview',
			'title'   => __( 'Overview', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );

		return $screen;
	}

	/**
	 * @param \WP_Screen $screen
	 *
	 * @return \WP_Screen
	 */
	public static function buildCodes( $screen ) {
		$screen->add_help_tab( [
			'id'      => 'overview',
			'title'   => __( 'Overview', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );

		return $screen;
	}

	/**
	 * @param \WP_Screen $screen
	 *
	 * @return \WP_Screen
	 */
	public static function buildGroups( $screen ) {
		$screen->add_help_tab( [
			'id'      => 'overview',
			'title'   => __( 'Overview', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );

		return $screen;
	}

	/**
	 * @param \WP_Screen $screen
	 *
	 * @return \WP_Screen
	 */
	public static function buildShortcodes( $screen ) {
		$screen->add_help_tab( [
			'id'      => 'overview',
			'title'   => __( 'Overview', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );

		return $screen;
	}

	/**
	 * @param \WP_Screen $screen
	 *
	 * @return \WP_Screen
	 */
	public static function buildSettings( $screen ) {
		$screen->add_help_tab( [
			'id'      => 'overview',
			'title'   => __( 'Overview', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );
		$screen->add_help_tab( [
			'id'      => 'main',
			'title'   => __( 'Main options', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );
		$screen->add_help_tab( [
			'id'      => 'targeting',
			'title'   => __( 'Targeting', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );
		$screen->add_help_tab( [
			'id'      => 'import|export',
			'title'   => __( 'Import/Export', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );
		$screen->add_help_tab( [
			'id'      => 'remote',
			'title'   => __( 'Remote management', 'rp-ads-manager' ),
			'content' => '<p>Coming soon!</p>'
		] );


		return $screen;
	}
}