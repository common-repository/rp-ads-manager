<?php

namespace RP\AdsManager\Hooks\Frontend;

use RP\AdsManager\Helper\AdHolder;
use RP\AdsManager\Helper\AdInjector;
use RP\AdsManager\Hooks\Hook;

/**
 * Class ThePost
 *
 * @package RP\AdsManager\Hooks\Frontend
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class ThePost extends Hook {
	protected $function = 'add_action';

	protected $args = [
		'the_post',
		[ ThePost::class, 'process' ],
		999,
	];

	public static $ready = false;

	public static $adMatrix = [];

	public static function process( $post ) {
		if ( self::$ready ) {
			global $wp_query;

			$place_id = AdInjector::getPlaceId();

			$current = $wp_query->current_post;
			$total   = $wp_query->post_count;
			$middle  = (int) floor( $total / 2 );

			$printed = false;

			if ( ! is_null( $place_id ) && $place_id > 2 && $current != 0 ) {

				if ( count( AdHolder::$box['after'] ) && array_key_exists( $current, AdHolder::$box['after'] ) ) {
					$randomKey = array_rand( AdHolder::$box['after'][ $current ] );

					$ad = AdHolder::$box['after'][ $current ][ $randomKey ];

					AdInjector::printit( $ad );

					$printed = true;
				}

				if ( ! $printed && count( AdHolder::$box['each'] ) ) {
					$randomEach = array_rand( AdHolder::$box['each'] );
					if ( $current % $randomEach == 0 ) {
						$randomKey = array_rand( AdHolder::$box['each'][ $randomEach ] );

						$ad = AdHolder::$box['each'][ $randomEach ][ $randomKey ];

						AdInjector::printit( $ad );

						$printed = true;
					}
				}

				if ( ! $printed && $current == $middle && count( AdHolder::$box['middle'] ) ) {
					$randomKey = array_rand( AdHolder::$box['middle'] );

					$ad = AdHolder::$box['middle'][ $randomKey ];

					AdInjector::printit( $ad );
				}
			}
		}
	}
}
