<?php

namespace RP\AdsManager\Hooks\Frontend;

use RP\AdsManager\Helper\AdHolder;
use RP\AdsManager\Helper\AdInjector;
use RP\AdsManager\Helper\Code;
use RP\AdsManager\Hooks\Hook;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class LoopStart extends Hook {
	protected $function = 'add_action';

	protected $args = [
		'loop_start',
		[ LoopStart::class, 'process' ],
		999,
	];

	/**
	 * @param \WP_Query $wp_query
	 */
	public static function process( $wp_query ) {

		$place_id = AdInjector::getPlaceId();

		if ( ! is_null( $place_id ) && $place_id > 2 ) {

			$ads = Code::instance()->byPlaceId( $place_id );

			AdHolder::init();

			foreach ( $ads as $ad ) {
				$ad['options'] = unserialize( $ad['options'] );

				if ( array_key_exists( 'policy', $ad['options'] ) && $ad['options']['policy'] !== 'none' ) {
					$ids = explode( ',', $ad['options']['ids'] );
					$id  = $wp_query->queried_object_id;

					if ( $ad['options']['policy'] === 'incl' && ! in_array( $id, $ids ) ) {
						continue;
					}

					if ( $ad['options']['policy'] === 'excl' && in_array( $id, $ids ) ) {
						continue;
					}
				}

				switch ( $ad['options']['position'] ) {
					case 'top':
					case 'middle':
					case 'bottom':
						AdHolder::$box[ $ad['options']['position'] ][] = $ad;
						break;
					case 'after-each':
						AdHolder::$box['each'][ $ad['options']['number'] ][] = $ad;
						break;
					case 'after-n-post':
						AdHolder::$box['after'][ $ad['options']['number'] ][] = $ad;
						break;
				}
			}

			if ( count( AdHolder::$box['top'] ) ) {
				$randomKey = array_rand( AdHolder::$box['top'] );

				$ad = AdHolder::$box['top'][ $randomKey ];

				AdInjector::printit( $ad );
			}

			ThePost::$ready = true;
		}
	}
}
