<?php

namespace RP\AdsManager\Hooks\Frontend;

use RP\AdsManager\Helper\Code;
use RP\AdsManager\Helper\AdInjector;
use RP\AdsManager\Helper\wpView;
use RP\AdsManager\Hooks\Hook;

class Content extends Hook {
	protected $function = 'add_filter';

	protected $args = [
		'the_content',
		[ Content::class, 'process' ],
		999,
	];

	public static function process( $content ) {
		if ( ! in_the_loop() ) {
			return $content;
		}

		if ( ( is_feed() ) || ( false !== strpos( $content, '<!--RPAM-OffAds-->' ) ) ) {
			return $content;
		}

		$place_id = AdInjector::getPlaceId();

		if ( in_array( $place_id, [ wpView::SINGLE, wpView::PAGE ] ) ) {
			$ads = Code::instance()->byPlaceId( $place_id );

			if ( count( $ads ) ) {
				foreach ( $ads as $ad ) {
					$ad['options'] = unserialize( $ad['options'] );

					if ( $ad['options']['policy'] !== 'none' ) {
						$ids = explode( ',', $ad['options']['ids'] );
						$id  = get_the_ID();

						if ( $ad['options']['policy'] === 'incl' && ! in_array( $id, $ids ) ) {
							continue;
						}

						if ( $ad['options']['policy'] === 'excl' && in_array( $id, $ids ) ) {
							continue;
						}
					}

					switch ( $ad['options']['position'] ) {
						case 'top':
							$content = AdInjector::before( $content, $ad );
							break;
						case 'middle':
							$content = AdInjector::middle( $content, $ad );
							break;
						case 'bottom':
							$content = AdInjector::after( $content, $ad );
							break;
						case 'after-n-p':
							$content = AdInjector::after( $content, $ad, 'n-p' );
							break;
						case 'before-last-p':
							$content = AdInjector::before( $content, $ad, 'last-p' );
							break;
						case 'after-more':
							$content = AdInjector::after( $content, $ad, 'more' );
							break;
						case 'after-n-img':
							$content = AdInjector::after( $content, $ad, 'n-img' );
							break;
						default:
					}
				}
			}
		}

		return $content;
	}
}
