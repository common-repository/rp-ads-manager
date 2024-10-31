<?php

final class loader_a5a1ec9f {
	const NS_PREFIX_LENGTH = 14;

	private static $instance;

	/** @var \RP\AdsManager\AdsManager|null */
	private $app = null;
	private $paths = [];

	public static function instance() {
		if ( ! self::$instance instanceof loader_a5a1ec9f ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function paths() {
		return $this->paths;
	}

	public function register( $path ) {
		if ( ! in_array( $path, $this->paths ) ) {
			$this->paths[] = $path;
		}

		return $this;
	}

	public function hooks() {
		$this->app = \RP\AdsManager\AdsManager::instance();

		register_activation_hook( __FILE__, [ $this->app, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this->app, 'deactivate' ] );

		return $this;
	}

	public function launch() {
		if ( null !== $this->app ) {
			$this->app->boot()
			          ->run();
		}
	}

	public function get( $fullClassName ) {
		$className = $this->prepare( $fullClassName );

		foreach ( $this->paths as $path ) {
			$fileToLoad = sprintf( '%s/src/%s.php', $path, $className );

			if ( is_file( $fileToLoad ) && is_readable( $fileToLoad ) ) {
				include_once $fileToLoad;
				break;
			}
		}
	}

	private function prepare( $className ) {
		return substr( str_replace( '\\', '/', $className ), self::NS_PREFIX_LENGTH );
	}

	private function __construct() {
		spl_autoload_register( [ $this, 'get' ] );
	}

	private function __clone() {
	}

	private function __wakeup() {
	}
}
