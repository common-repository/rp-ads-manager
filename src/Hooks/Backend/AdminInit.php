<?php

namespace RP\AdsManager\Hooks\Backend;

use RP\AdsManager\Helper\Code;
use RP\AdsManager\Helper\Group;
use RP\AdsManager\Helper\Shortcode;
use RP\AdsManager\Hooks\Hook;
use RP\AdsManager\Util\Request;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class AdminInit extends Hook {
	/**
	 * @var string
	 */
	protected $function = 'add_action';

	/**
	 * @var array
	 */
	protected $args = [
		'admin_init',
		[ AdminInit::class, 'process' ]
	];

	private static function helper( $page ) {
		switch ( $page ) {
			case 'codes':
				return Code::instance();
			case 'groups':
				return Group::instance();
            case 'shortcodes':
                return Shortcode::instance();
			default:
				return null;
		}
	}

	public static function process() {
		$request = Request::instance();

		$notice = get_transient( 'rpam_notice' );

		if ( $notice ) {
			add_action( 'admin_notices', function () use ( $notice ) { ?>

                <div class="notice notice-success is-dismissible">
                    <p><?= $notice ?></p>
                </div>

			<?php } );
		}

		if ( null !== $request->page() && $request->isAjax() ) {
			return __return_false();
		}

		if ( $request->isProccessable() ) {
			$helper = self::helper( $request->page() );

			if ( null === $helper ) {
				return __return_false();
			}

			$id = $request->get( 'id' );

			if ( null !== $request->action() ) {
				$ids = $request->isBulk() ? $request->get( 'ids' ) : [ $id ];

				if ( method_exists( $helper, $request->action() ) ) {
					call_user_func( [ $helper, $request->action() ], $ids );
				}
			}

			if ( $request->isPost() ) {
				if ( null === $id ) {
					$id = $helper->create( $request );
				} else {
					$helper->update( $request, $id );
				}

				header( 'Location: ' . admin_url( 'admin.php?page=rpam-' . $request->page() . '&view=' . $request->get( 'view' ) . '&id=' . $id ) );
				exit;
			}

			header( 'Location: ' . admin_url( 'admin.php?page=rpam-' . $request->page() ) );
			exit;
		}

		return __return_false();
	}
}
