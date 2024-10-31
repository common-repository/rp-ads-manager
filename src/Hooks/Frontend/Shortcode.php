<?php

namespace RP\AdsManager\Hooks\Frontend;

use RP\AdsManager\Helper\Block;
use RP\AdsManager\Helper\Code;
use RP\AdsManager\Helper\wpView;
use RP\AdsManager\Hooks\Hook;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Shortcode extends Hook {
	protected $function = 'add_shortcode';

	protected $args = [
		'rpam_place',
		[ Shortcode::class, 'register' ]
	];

	public static function register( $atts = [], $content = null ) {

		$params = shortcode_atts( [
			'id' => null
		], $atts );

		if ( null !== $params['id'] ) {
			$ads = Code::instance()->byPlaceId( wpView::SHORTCODE, $params['id'] );

			if ( count( $ads ) ) {
				$randomKey = array_rand( $ads );

				$content = Block::from( $ads[ $randomKey ] );
			}
		}

		return $content;
	}
}
