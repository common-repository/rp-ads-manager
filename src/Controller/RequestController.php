<?php

namespace RP\AdsManager\Controller;

use RP\AdsManager\AdsManagerController;
use RP\AdsManager\Util\Request;
use RP\AdsManager\Util\Response;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
abstract class RequestController extends AdsManagerController {

	private function preRender() {
		\wp_enqueue_style( 'rp-ads-manager-style', \plugins_url( 'rp-ads-manager/assets/style.css' ) );
	}

	public function execute( $action = null ) {
		$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING ) ?: false;

		if ( $view ) {
			$action .= ucfirst( $view );
		}

		$template = sprintf( 'backend/%s.php', $action );
		$method   = sprintf( '%sAction', $action );

		if ( method_exists( $this, $method ) ) {
			$response = call_user_func( [ $this, $method ], Request::instance() );
			if ( $response instanceof Response ) {
				$this->render( $response->template(), $response->vars() );
			} elseif ( is_array( $response ) ) {
				$this->render( $template, $response );
			} else {
				$this->render( 'error', [] );
			}
		} else {
			$this->render( $template, [] );
		}
	}

	/**
	 * @param string $template
	 * @param array $vars
	 */
	public function render( $template, $vars ) {
		$this->preRender();

		$paths = \loader_a5a1ec9f::instance()->paths();

		foreach ( $paths as $path ) {
			$templatePath = sprintf( '%s/templates/%s', $path, $template );

			if ( file_exists( $templatePath ) ) {
				break;
			}
		}

		if ( ! file_exists( $templatePath ) ) {
			$templatePath = sprintf( '%s/templates/%s', RPAM_PATH, 'not_found.php' );
		}

		\extract( $vars );
		\ob_start();
		require_once $templatePath;
		$output = \ob_get_contents();
		\ob_end_clean();

		echo $output;
	}
}