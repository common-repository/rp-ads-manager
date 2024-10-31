<?php

namespace RP\AdsManager\Hooks\Frontend;

use RP\AdsManager\Hooks\Hook;
use RP\AdsManager\WP\Settings;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Head extends Hook {
	const SETTINGS_KEY = 'rpam_field_codeheader';

	protected $function = 'add_action';

	protected $args = [
		'wp_head',
		[ Head::class, 'process' ],
	];

	public static function process() {
		echo Settings::instance()->get( self::SETTINGS_KEY );
	}
}
