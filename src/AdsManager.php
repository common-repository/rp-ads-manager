<?php

namespace RP\AdsManager;

use RP\AdsManager\Controller\Api;
use RP\AdsManager\Controller\Backend;
use RP\AdsManager\Controller\Frontend;
use RP\AdsManager\Database\Connection;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class AdsManager {
	const RT_OPTION = 'rpam_remote_token';

	private static $_instance = null;

	private $remoteToken;
	private $dbConnection;

	private function __construct() {
		$this->remoteToken = get_option( self::RT_OPTION, null );
		if ( null === $this->remoteToken ) {
			$this->remoteToken = password_hash( parse_url( get_option( 'siteurl' ), PHP_URL_HOST ), PASSWORD_BCRYPT, [ "cost" => 8 ] );
			update_option( self::RT_OPTION, $this->remoteToken );
		}

		$this->dbConnection = Connection::instance();
	}

	private function __clone() {
	}

	private function __wakeup() {
	}

	public static function instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function getRemoteToken() {
		return $this->remoteToken;
	}

	public function boot() {
		$this->dbConnection->validate();

		return $this;
	}

	public function run() {
		/** @var Backend|Frontend|Api $className */
		$className = $this->detectUnit();

		if ( class_exists( $className ) ) {
			$className::instance()
			          ->prepare()
			          ->launch();

		}
	}

	private function detectUnit() {
		$unit = null;

		if ( \is_admin() ) {
			$unit = Backend::class;
		} elseif ( false !== strpos( $_SERVER['REQUEST_URI'], '/' . \rest_get_url_prefix() ) ) {
			$unit = Api::class;
		} else {
			$unit = Frontend::class;
		}

		return $unit;
	}

	/**
	 * Activation hook
	 */
	public function activate() {
		$this->dbConnection->validate();
	}

	/**
	 * Deactivation hook
	 */
	public function deactivate() {
		foreach ( \get_plugins() as $plugin => $info ) {
			if ( false !== \strpos( $plugin, 'rp-ads-mod-' ) ) {
				\deactivate_plugins( $plugin, true, false );
			}
		}
	}
}
