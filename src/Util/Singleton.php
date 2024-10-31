<?php

namespace RP\AdsManager\Util;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
abstract class Singleton {
	/**
	 * @var array
	 */
	private static $_instances = [];

	/**
	 * @return static
	 */
	public static function instance() {
		if ( ! array_key_exists( static::class, self::$_instances ) ) {
			self::$_instances[ static::class ] = new static;
		}

		return self::$_instances[ static::class ];
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct() {
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone() {
	}

	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 *
	 * @return void
	 */
	private function __wakeup() {
	}
}
