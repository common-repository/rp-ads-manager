<?php

namespace RP\AdsManager\Helper;

use RP\AdsManager\Util\Singleton;
use RP\AdsManager\WP\Shortcode;
use RP\AdsManager\WP\Widget;

/**
 * Class wpView
 *
 * @package RP\AdsManager\Helper
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class wpView extends Singleton {
	const SINGLE = 1;
	const PAGE = 2;
	const HOMEPAGE = 3;
	const CATEGORY = 4;
	const ARCHIVE = 5;
	const TAG = 6;
	const SHORTCODE = 998;
	const WIDGET = 999;

	private static $slug = [
		self::SINGLE    => 'single',
		self::PAGE      => 'page',
		self::HOMEPAGE  => 'homepage',
		self::CATEGORY  => 'category',
		self::ARCHIVE   => 'archive',
		self::TAG       => 'tag',
		self::SHORTCODE => 'shortcode',
		self::WIDGET    => 'widget',
	];

	private $options;

	protected function __construct() {

		$positions = [
			'top'    => __( 'at the beginning', 'rp-ads-manager' ),
			'middle' => __( 'in the middle', 'rp-ads-manager' ),
			'bottom' => __( 'in the end', 'rp-ads-manager' ),
		];

		$pagePositions = array_merge(
			$positions,
			[
				'after-n-p'     => __( 'after Paragraph #{N}', 'rp-ads-manager' ),
				'before-last-p' => __( 'before last Paragraph', 'rp-ads-manager' ),
				'after-n-img'   => __( 'after Image #{N}', 'rp-ads-manager' ),
			]
		);

		$singlePositions = array_merge(
			$pagePositions,
			[
				'after-more' => esc_html__( 'after <!--more--> tag', 'rp-ads-manager' ),
			]
		);

		$listPositions = array_merge(
			$positions,
			[
				'after-each'   => __( 'after each {N} Post', 'rp-ads-manager' ),
				'after-n-post' => __( 'after Post  #{N}', 'rp-ads-manager' ),
			]
		);

		$this->options = [
			self::$slug[ self::SINGLE ]   => [
				'title'     => __( 'Single (Post)', 'rp-ads-manager' ),
				'positions' => $singlePositions,
			],
			self::$slug[ self::PAGE ]     => [
				'title'     => __( 'Page', 'rp-ads-manager' ),
				'positions' => $pagePositions,
			],
			self::$slug[ self::HOMEPAGE ] => [
				'title'     => __( 'Homepage', 'rp-ads-manager' ),
				'positions' => $listPositions,
			],
			self::$slug[ self::CATEGORY ] => [
				'title'     => __( 'Category', 'rp-ads-manager' ),
				'positions' => $listPositions,
			],
			self::$slug[ self::ARCHIVE ]  => [
				'title'     => __( 'Archive', 'rp-ads-manager' ),
				'positions' => $listPositions,
			],
			self::$slug[ self::TAG ]      => [
				'title'     => __( 'Tag', 'rp-ads-manager' ),
				'positions' => $listPositions,
			],
			self::$slug[ self::SHORTCODE ]   => [
				'title'     => __( 'Shortcode', 'rp-ads-manager' ),
				'positions' => Shortcode::stock()
			],
			self::$slug[ self::WIDGET ]   => [
				'title'     => __( 'Widget', 'rp-ads-manager' ),
				'positions' => Widget::stock()
			]
		];
	}

	public function idFromSlug( $slug ) {
		$const = wpView::class . '::' . strtoupper( $slug );

		return defined( $const ) ? constant( $const ) : null;
	}

	public function getSlug( $id, $title = false ) {
		return array_key_exists( $id, self::$slug ) ? ( $title ? $this->getTitle( $id ) : self::$slug[ $id ] ) : false;
	}

	private function getTitle( $id ) {
		return $this->options[ self::$slug[ $id ] ]['title'];
	}

	public function getOptions() {
		return $this->options;
	}

	public function getPlaces() {
		$places = [];

		foreach ( $this->options as $key => $option ) {
			$places[ $key ] = $option['title'];
		}

		return $places;
	}

	public function getPositions() {
		$positions = [];

		//TODO: improve positions array
		foreach ( $this->options as $key => $viewOption ) {
			if ( 'widget' === $key ) {
				//echo '<pre>'; print_r( $viewOption );echo '</pre>';
			} else {
				$positions = array_merge( $positions, $viewOption['positions'] );
			}

		}

		return $positions;
	}
}