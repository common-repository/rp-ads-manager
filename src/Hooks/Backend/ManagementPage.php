<?php

namespace RP\AdsManager\Hooks\Backend;

use RP\AdsManager\Hooks\Hook;
use RP\AdsManager\WP\Menu;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class ManagementPage extends Hook {
	/**
	 * @var string
	 */
	protected $function = 'add_action';

	/**
	 * @var array
	 */
	protected $args = [
		'admin_menu',
		[ Menu::class, 'build' ],
		100,
	];
}
