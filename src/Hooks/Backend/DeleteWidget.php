<?php

namespace RP\AdsManager\Hooks\Backend;

use RP\AdsManager\Database\Query;
use RP\AdsManager\Database\Schema;
use RP\AdsManager\Helper\Code;
use RP\AdsManager\Helper\wpView;
use RP\AdsManager\Hooks\Hook;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class DeleteWidget extends Hook {
	/**
	 * @var string
	 */
	protected $function = 'add_action';

	/**
	 * @var array
	 */
	protected $args = [
		'delete_widget',
		[ DeleteWidget::class, 'execute' ],
	];

	public static function execute( $widget_id ) {
		$ads = Code::instance()->byPlaceId( wpView::WIDGET, $widget_id, false );

		$query = Query::instance();

		foreach ( $ads as $ad ) {
			$query->delete( Schema::OPT_TABLE, [ 'code_id' => $ad['id'], 'place_id' => wpView::WIDGET ] );
		}
	}
}
