<?php

namespace RP\AdsManager\Hooks\Frontend;

use RP\AdsManager\Hooks\Hook;
use RP\AdsManager\WP\Settings;

class Footer extends Hook {
	const SETTINGS_KEY = 'rpam_field_codefooter';

	protected $function = 'add_action';

	protected $args = [
		'wp_footer',
		[ Footer::class, 'process' ],
	];

	public static $js = [];

	public static function process() {

		echo implode( "\n", self::$js );

		echo Settings::instance()->get( self::SETTINGS_KEY );
	}
}
