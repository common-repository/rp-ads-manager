<?php

namespace RP\AdsManager\Util;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
final class Response {

	/**
	 * @var string
	 */
	private $template = '';

	/**
	 * @var array
	 */
	private $vars = [];

	/**
	 * Response constructor.
	 *
	 * @param $template
	 * @param $vars
	 */
	public function __construct( $template, $vars ) {
		$this->template = $template;
		$this->vars     = $vars;
	}

	/**
	 * @return string
	 */
	public function template() {
		return $this->template;
	}

	/**
	 * @return array
	 */
	public function vars() {
		return $this->vars;
	}
}