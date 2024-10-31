<?php

namespace RP\AdsManager\Helper;

/**
 * Class AdInjector
 *
 * @package RP\AdsManager\Helper
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class AdInjector {
	public static function getPlaceId() {
		return is_single() ? wpView::SINGLE : (
		is_page() ? wpView::PAGE : (
		is_home() ? wpView::HOMEPAGE : (
		is_category() ? wpView::CATEGORY : (
		is_archive() && ! is_tag() ? wpView::ARCHIVE : (
		is_tag() ? wpView::TAG :
			null ) ) ) ) );
	}

	public static function printit( $ad ) {
		echo Block::from( $ad );
	}

	public static function before( $content, $ad, $type = false ) {
		switch ( $type ) {
			case 'last-p':
				$countItems  = substr_count( $content, '</p>' );
				$afterNumber = $countItems - 1;
				$content_arr = explode( '</p>', $content );

				$content_arr[ $afterNumber ] = Block::from( $ad ) . $content_arr[ $afterNumber ];

				$content = implode( '</p>', $content_arr );
				break;
			default:
				$content = Block::from( $ad ) . $content;
		}

		return $content;
	}

	public static function middle( $content, $ad ) {

		if ( substr_count( $content, '</p>' ) > 1 ) {
			$content_arr            = explode( '</p>', $content );
			$middle                 = (int) floor( count( $content_arr ) / 2 );
			$content_arr[ $middle ] = Block::from( $ad ) . $content_arr[ $middle ];
			$content                = implode( '</p>', $content_arr );
		}

		return $content;
	}

	public static function after( $content, $ad, $type = false ) {
		switch ( $type ) {
			case 'n-img':
				$countItems  = substr_count( $content, '<img' );
				$afterNumber = (int) $ad['options']['number'];
				$inTheEnd    = array_key_exists( 'in_the_end', $ad['options'] );

				$afterNumber = $countItems < $afterNumber && $inTheEnd ? $countItems : $afterNumber;

				if ( $afterNumber <= $countItems ) {
					$matches = [];
					preg_match_all( '#(<a[^>]*>)?\s*(<img[^>]*>)\s*(</a>)?#iu', $content, $matches, PREG_OFFSET_CAPTURE );

					if ( ! empty( $matches[0] ) ) {
						$matchReplace = $matches[0][ $afterNumber - 1 ][0];
						$offset       = $matches[0][ $afterNumber - 1 ][1];

						$content = substr( $content, 0, $offset )
						           . preg_replace( '#' . preg_quote( $matchReplace, '#' ) . '#', $matchReplace . Block::from( $ad ), substr( $content, $offset ), 1 );
					}
				}

				break;
			case 'more':
				$postid  = get_the_ID();
				$content = str_replace( '<span id="more-' . $postid . '"></span>', Block::from( $ad ), $content );
				break;
			case 'n-p':
				$countItems  = substr_count( $content, '</p>' );
				$afterNumber = (int) $ad['options']['number'];
				$inTheEnd    = array_key_exists( 'in_the_end', $ad['options'] );

				$content_arr = explode( '</p>', $content );

				if ( $countItems >= $afterNumber ) {
					$content_arr[ $afterNumber ] = Block::from( $ad ) . $content_arr[ $afterNumber ];
				} elseif ( $countItems < $afterNumber && $inTheEnd ) {
					$afterNumber = $countItems;

					$content_arr[ $afterNumber ] = Block::from( $ad ) . $content_arr[ $afterNumber ];
				}

				$content = implode( '</p>', $content_arr );
				break;
			default:
				$content .= Block::from( $ad );
		}

		return $content;
	}
}
