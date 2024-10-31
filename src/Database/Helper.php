<?php

namespace RP\AdsManager\Database;

use RP\AdsManager\Util\Singleton;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Helper extends Singleton {
	/** @var Query  */
	protected $query;

	protected function __construct() {
		$this->query = Query::instance();
	}
}