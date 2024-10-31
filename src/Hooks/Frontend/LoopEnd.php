<?php

namespace RP\AdsManager\Hooks\Frontend;

use RP\AdsManager\Helper\AdHolder;
use RP\AdsManager\Helper\AdInjector;
use RP\AdsManager\Hooks\Hook;

/**
 * Class LoopEnd
 *
 * @package RP\AdsManager\Hooks\Frontend
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class LoopEnd extends Hook {
	protected $function = 'add_action';

	protected $args = [
		'loop_end',
		[ LoopEnd::class, 'process' ],
		999,
	];

	public static function process( $wp_query ) {

		$place_id = AdInjector::getPlaceId();

		if ( ! is_null( $place_id ) && $place_id > 2 ) {

			if ( count( AdHolder::$box['bottom'] ) ) {
				$randomKey = array_rand( AdHolder::$box['bottom'] );

				$ad = AdHolder::$box['bottom'][ $randomKey ];

				AdInjector::printit( $ad );
			}
		}
	}
}
