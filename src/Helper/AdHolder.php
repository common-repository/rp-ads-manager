<?php

namespace RP\AdsManager\Helper;

/**
 * Class AdHolder
 *
 * @package RP\AdsManager\Helper
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class AdHolder {
	public static $box = [];

	public static function init() {
		self::$box = [
			'top'    => [],
			'middle' => [],
			'bottom' => [],
			'each'   => [],
			'after'  => [],
		];
	}
}
