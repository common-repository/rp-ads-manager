<?php

namespace RP\AdsManager\WP;

use RP\AdsManager\Database\Query;
use RP\AdsManager\Database\Schema;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Shortcode {

	public static function stock( $api = false ) {
		$shortcodes = Query::instance()
		                   ->select()
		                   ->from( Schema::STC_TABLE, 's' )
		                   ->fetch();

		if ( $api ) {
			return $shortcodes;
		}

		$viewShortcodes = [];

		foreach ( $shortcodes as $shortcode ) {
			$viewShortcodes[ $shortcode['id'] ] = $shortcode['title'];
		}

		return $viewShortcodes;
	}

	public static function register() {

	}

}
