<?php

namespace RP\AdsManager\Hooks\Backend;

use RP\AdsManager\Hooks\Hook;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Settings extends Hook {
	/**
	 * @var string
	 */
	protected $function = 'add_action';
	/**
	 * @var array
	 */
	protected $args = [
		'admin_init',
		[ Settings::class, 'init' ]
	];

	/**
	 *
	 */
	public static function init() {
		\RP\AdsManager\WP\Settings::instance()->register();
	}
}
