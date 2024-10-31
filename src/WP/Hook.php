<?php

namespace RP\AdsManager\WP;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
abstract class Hook {
	/**
	 * @var string
	 */
	protected $function;

	/**
	 * @var array
	 */
	protected $args;

	/**
	 * @return string
	 */
	public function getFunction() {
		return $this->function;
	}

	/**
	 * @return array
	 */
	public function getArgs() {
		return $this->args;
	}
}
