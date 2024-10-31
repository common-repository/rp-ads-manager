<?php

namespace RP\AdsManager\Hooks;

/**
 * Class Widget
 *
 * @package RP\AdsManager\Hooks
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Widget extends Hook {
	/**
	 * @var string
	 */
	protected $function = 'add_action';

	/**
	 * @var array
	 */
	protected $args = [
		'widgets_init',
		[ Widget::class, 'processWidgets' ],
	];


	public static function processWidgets() {
		if ( ! function_exists( 'register_widget' ) ) {
			return;
		};

		register_widget('RP\\AdsManager\\Helper\\AdWidget');
	}
}
