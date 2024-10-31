<?php

namespace RP\AdsManager\Hooks;

/**
 * Class Hook
 *
 * @package RP\AdsManager
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
