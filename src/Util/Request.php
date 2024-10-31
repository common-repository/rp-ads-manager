<?php

namespace RP\AdsManager\Util;

final class Request extends Singleton {
	const GET = 1;
	const POST = 2;

	private $query = [];
	private $request = [];

	private $page = null;
	private $pages = [ 'dashboard', 'codes', 'groups', 'shortcodes', 'settings' ];

	private $action = null;
	private $actions = [ 'activate', 'deactivate', 'delete' ];

	private $bulk = false;
	private $post = false;

	private $ajax = false;

	protected function __construct() {
		$this->parseRequest();
	}

	public function has( $key, $type = Request::GET ) {
		switch ( $type ) {
			case self::POST:
				return array_key_exists( $key, $this->request );
				break;
			default:
				return array_key_exists( $key, $this->query );
		}
	}

	public function get( $key, $type = Request::GET ) {
		$value = null;

		if ( $this->has( $key, $type ) ) {
			switch ( $type ) {
				case self::POST:
					$value = $this->request[ $key ];
					break;
				default:
					$value = $this->query[ $key ];
			}
		}

		return $value;
	}

	public function page() {
		return $this->page;
	}

	public function action() {
		return $this->action;
	}

	public function isProccessable() {
		return $this->post || ( $this->bulk || null !== $this->action );
	}

	public function isPost() {
		return $this->post;
	}

	public function isBulk() {
		return $this->bulk;
	}

	public function isAjax() {
		return $this->ajax;
	}

	private function parseRequest() {
		$this->ajax = array_key_exists( 'HTTP_X_REQUESTED_WITH', $_SERVER );

		foreach ( $_GET as $key => $value ) {
			if ( $key == 'page' ) {
				$value = str_replace( 'rpam-', '', $value );
				if ( in_array( $value, $this->pages ) ) {
					$this->page = $value;
				}
				continue;
			}

			if ( $key == 'action' ) {
				if ( in_array( $value, $this->actions ) ) {
					$this->action = $value;
				}
				continue;
			}

			if ( in_array( $key, [ 'bulk-at', 'bulk-ab' ] ) ) {
				if ( $value != - 1 ) {
					if ( in_array( $value, $this->actions ) ) {
						$this->action = $value;
						$this->bulk   = true;
					}
				}
				continue;
			}

			$this->query[ $key ] = $value;
		}

		if ( ! empty( $_POST ) ) {
			$this->post = true;

			foreach ( $_POST as $key => $value ) {
				$this->request[ $key ] = $value;
			}
		}
	}

}
