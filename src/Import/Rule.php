<?php

namespace RP\AdsManager\Import;

abstract class Rule {
	/** @var string */
	protected $plugin_key = null;
	/** @var string */
	protected $settings_key = null;

	public function getPluginKey() {
		return $this->plugin_key;
	}

	public function getSettingsKey() {
		return $this->settings_key;
	}

	abstract public function getPortedConfig();
}
