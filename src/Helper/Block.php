<?php

namespace RP\AdsManager\Helper;

use RP\AdsManager\Hooks\Frontend\Footer;

/**
 * Class Block
 *
 * @package RP\AdsManager\Helper
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Block {
	private static $template = '<div##STYLE####CLASSES##>##CONTENT##</div>';

	private static $styles = [
		'none'   => '',
		'left'   => ' style="float:left"',
		'center' => ' style="float:none;text-align:center"',
		'right'  => ' style="float:right"'
	];

	public static function from( $data ) {

		if ( 's:' === substr( $data['code'], 0, 2 ) ) {
			$data['code'] = stripslashes( unserialize( $data['code'] ) );
		}

		if ( is_string( $data['options'] ) ) {
			$data['options'] = unserialize( $data['options'] );
		}

		$block = str_replace( [
			'##STYLE##',
			'##CLASSES##',
			'##CONTENT##'
		], [
			self::$styles[ $data['options']['aligment'] ],
			'' === $data['options']['class'] ? '' : ' class="' . $data['options']['class'] . '"',
			$data['code']
		], self::$template );

		if ( ! is_null( $data['init'] ) && is_string( $data['init'] ) && !array_key_exists((int)$data['id'], Footer::$js) ) {
			Footer::$js[(int)$data] = stripslashes( unserialize( $data['init'] ) );
		}

		return $block;
	}
}
