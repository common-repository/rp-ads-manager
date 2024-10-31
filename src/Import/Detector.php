<?php

namespace RP\AdsManager\Import;

use RP\AdsManager\Helper\Singleton;

class Detector extends Singleton {

	private $rules = [];

	protected function __construct() {
		$this->loadRules();
	}

	private function loadRules() {
		$files = scandir( RPAM_PATH . '/src/Import/Rules' );

		$pluginKeys = array_keys( get_plugins() );

		foreach ( $files as $file ) {
			if ( in_array( $file, [ '.', '..' ] ) ) {
				continue;
			}

			$ruleClass = 'RP\\AdsManager\\Import\\Rules\\' . substr( $file, 0, - 4 );

			/** @var Rule $rule */
			$rule = new $ruleClass;

			if ( in_array( $rule->getPluginKey(), $pluginKeys ) ) {
				$this->rules[$rule->getPluginKey()] = $rule;
				continue;
			}

			$testOptions = get_option( $rule->getSettingsKey() );
			if ( false !== $testOptions ) {
				$this->rules[$rule->getPluginKey()] = $rule;
			}
		}
	}

	public function getRule( $key ) {
		return array_key_exists( $key, $this->rules ) ? $this->rules[ $key ] : false;
	}

	public function getRules() {
		return $this->rules;
	}
}
