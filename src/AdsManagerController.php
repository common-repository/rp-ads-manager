<?php

namespace RP\AdsManager;

use RP\AdsManager\Util\Singleton;
use RP\AdsManager\WP\Hook;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
abstract class AdsManagerController extends Singleton {
	protected $_functions = [];

	public function prepare() {
		$cargoBay = $this->scan();

		foreach ( $cargoBay as $container ) {
			if ( class_exists( $container ) ) {
				/** @var Hook $package */
				$package = new $container;

				$this->bind( $package->getFunction(), $package->getArgs() );
			}
		}

		return $this;
	}

	public function launch() {
		foreach ( $this->_functions as $function ) {
			call_user_func_array( $function['name'], $function['args'] );
		}
	}

	private function bind( $function, $args ) {
		$this->_functions[] = [
			'name' => $function,
			'args' => $args,
		];
	}

	private function scan() {
		$cargoBay = [];

		$decks = \loader_a5a1ec9f::instance()->paths();
		$key   = substr( static::class, strrpos( static::class, '\\' ) + 1 );

		foreach ( $decks as $deck ) {
			$stock = realpath( sprintf( '%s/src/Hooks/%s', $deck, $key ) );
			if ( false === $stock && ! is_dir( $stock ) ) {
				continue;
			}

			$containers = scandir( $stock );

			foreach ( $containers as $container ) {
				if ( in_array( $container, [ '.', '..' ] ) ) {
					continue;
				}

				$cargoBay[] = sprintf( 'RP\\AdsManager\\Hooks\\%s\\%s', $key, substr( $container, 0, - 4 ) );
			}
		}

		return $cargoBay;
	}
}