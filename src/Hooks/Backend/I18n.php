<?php

namespace RP\AdsManager\Hooks\Backend;

use RP\AdsManager\Hooks\Hook;

/**
 * Hook I18n
 *
 * @package RP\AdsManager\Hooks\Backend
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class I18n extends Hook {
	/**
	 * @var string
	 */
	protected $function = 'add_action';

	/**
	 * @var array
	 */
	protected $args = [
		'plugins_loaded',
		[ I18n::class, 'Load' ],
		100,
	];

	/**
	 *
	 */
	public static function Load() {
		load_plugin_textdomain(
			'rp-ads-manager',
			false,
			plugin_basename( RPAM_PATH ) . '/languages/'
		);
	}
}
