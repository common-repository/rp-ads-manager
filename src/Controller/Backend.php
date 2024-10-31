<?php

namespace RP\AdsManager\Controller;

use RP\AdsManager\Database\Query;
use RP\AdsManager\Database\Schema;
use RP\AdsManager\Helper\Tabs;
use RP\AdsManager\Helper\wpView;
use RP\AdsManager\Util\Converter;
use RP\AdsManager\Util\Request;
use RP\AdsManager\Util\Response;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Backend extends RequestController {

	public function dashboardAction() {
		return [];
	}

	public function codesAction() {
		$defaultFilter = [
			'status' => '',
			'group'  => '',
			'p3e'    => '',
			'p6n'    => '',
		];

		$filter = [];

		$filter['status'] = filter_input( INPUT_GET, 'status', FILTER_SANITIZE_STRING ) ?: $defaultFilter['status'];
		$filter['group']  = filter_input( INPUT_GET, 'group', FILTER_SANITIZE_STRING ) ?: $defaultFilter['group'];
		$filter['p3e']    = filter_input( INPUT_GET, 'p3e', FILTER_SANITIZE_STRING ) ?: $defaultFilter['p3e'];
		$filter['p6n']    = filter_input( INPUT_GET, 'p6n', FILTER_SANITIZE_STRING ) ?: $defaultFilter['p6n'];

		$dbQuery = Query::instance();

		$filterArray = [];
		foreach ( $filter as $type => $value ) {
			if ( $value == '' ) {
				continue;
			}

			switch ( $type ) {
				case 'status':
					$filterArray[] = sprintf( '`c`.`enabled` = %s', ( $value == 'active' ? '1' : '0' ) );
					break;
				case 'group':
					if ( $value == 'none' ) {
						$filterArray[] = '`c`.`group_id` IS NULL';
					} else {
						$filterArray[] = sprintf( '`c`.`group_id` = \'%s\'', $value );
					}
					break;
				case 'p3e':
					$filterArray[] = '`o`.`place_id` = ' . wpView::instance()->idFromSlug( $value );
					break;
				case 'p6n':
					$filterArray[] = "`o`.`options` LIKE '%:\"{$value}\"%'";
					break;
			}
		}


		$codesQuery = $dbQuery->select( '`c`.`id`, `c`.`title`, `c`.`enabled`, `g`.`title` as `group`, `o`.`place_id`, `o`.`options`' )
		                      ->from( Schema::CDS_TABLE, 'c' )
		                      ->join( Schema::GRP_TABLE, 'g', '`c`.`group_id` = `g`.`id`' )
		                      ->join( Schema::OPT_TABLE, 'o', '`c`.`id` = `o`.`code_id`' );

		if ( ! empty( $filterArray ) ) {
			$codesQuery->where( implode( ' AND ', $filterArray ) );
		}

		$codes = $codesQuery->fetch();

		$codes = Converter::run( Converter::CODE_VIEW_LIST, $codes );

		$counters = $dbQuery->select( 'COUNT(*) total, COALESCE(SUM(CASE WHEN `c`.enabled = \'1\' THEN 1 ELSE 0 END), 0) active, COALESCE(SUM(CASE WHEN `c`.`enabled` = \'0\' THEN 1 ELSE 0 END), 0) inactive' )
		                    ->from( Schema::CDS_TABLE, 'c' )
		                    ->fetch( Query::SINGLE );

		$dbGroups = $dbQuery->select()
		                    ->from( Schema::GRP_TABLE, 'g' )
		                    ->fetch();

		$groups = [
			'none' => __( 'No group', 'rp-ads-manager' )
		];

		foreach ( $dbGroups as $group ) {
			$groups[ $group['id'] ] = $group['title'];
		}

		$actions = [
			'-1'         => __( 'Bulk Actions', 'rp-ads-manager' ),
			'activate'   => __( 'Activate', 'rp-ads-manager' ),
			'deactivate' => __( 'Deactivate', 'rp-ads-manager' ),
			'delete'     => __( 'Delete', 'rp-ads-manager' )
		];

		/** @var wpView $wpView */
		$wpView = wpView::instance();

		return new Response( 'backend/codes/list.php', [
			'updated'   => get_transient( 'rpam_quick_action' ),
			'actions'   => $actions,
			'filter'    => $filter,
			'counters'  => $counters,
			'records'   => $codes,
			'places'    => $wpView->getPlaces(),
			'positions' => $wpView->getPositions(),
			'groups'    => $groups
		] );
	}

	public function codesEditorAction() {

		$record = [
			'id'     => null,
			'title'  => '',
			'code'   => '',
			'init'   => null,
			'group'  => 'none',
			'places' => [],
		];

		$dbQuery = Query::instance();

		if ( array_key_exists( 'id', $_GET ) ) {

			$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );

			$record = Query::instance()
			               ->select( '`c`.`id`, `c`.`title`, `c`.`code`, `c`.`init` , `c`.`enabled`, `g`.`title` as `group`, `o`.`place_id`, `o`.`options`' )
			               ->from( Schema::CDS_TABLE, 'c' )
			               ->join( Schema::GRP_TABLE, 'g', '`c`.`group_id` = `g`.`id`' )
			               ->join( Schema::OPT_TABLE, 'o', '`c`.`id` = `o`.`code_id`' )
			               ->where( sprintf( '`c`.`id` = %s', $id ) )
			               ->fetch();

			$record = Converter::run( Converter::CODE_VIEW_SINGLE, $record );

			if ( array_key_exists( 'action', $_GET ) && $_GET['action'] == 'copy' ) {
				$record['id']    = null;
				$record['title'] .= ' — ' . __( 'Copy', 'rp-ads-manager' );
			}
		}

		$dbGroups = $dbQuery->select( '`g`.`title`' )
		                    ->from( Schema::GRP_TABLE, 'g' )
		                    ->fetch();

		$groups = [
			null => __( 'No group', 'rp-ads-manager' )
		];

		foreach ( $dbGroups as $group ) {
			$groups[ $group['title'] ] = $group['title'];
		}

		wp_enqueue_code_editor( [ 'type' => 'text/html' ] );
		wp_enqueue_script( 'rp-ads-manager-lib', plugins_url( 'rp-ads-manager/assets/selectize/selectize.js' ) );
		wp_enqueue_style( 'rp-ads-manager-lib', plugins_url( 'rp-ads-manager/assets/selectize/selectize.default.css' ) );
		wp_enqueue_script( 'rp-ads-manager-editor', plugins_url( 'rp-ads-manager/assets/editor.js' ) );

		return new Response( 'backend/codes/editor.php', [
			'saved'          => get_transient( 'rpam_saved' ),
			'record'         => $record,
			'places'         => wpView::instance()->getOptions(),
			'groups'         => $groups,
			'hasViewOptions' => [
				'after-n-p',
				'after-n-img',
				'after-n-post',
				'after-each',
			],
		] );
	}

	public function groupsAction() {
		$groups = Query::instance()
		               ->select()
		               ->from( Schema::GRP_TABLE, 'g' )
		               ->fetch();

		$actions = [
			'-1'     => __( 'Bulk Actions', 'rp-ads-manager' ),
			'delete' => __( 'Delete', 'rp-ads-manager' )
		];

		return new Response( 'backend/groups/list.php', [
			'records' => $groups,
			'actions' => $actions
		] );
	}

	public function groupsEditorAction( Request $request ) {
		$record = [
			'id'    => null,
			'title' => ''
		];

		if ( $request->has( 'id' ) ) {
			$record = Query::instance()
			               ->select()
			               ->from( Schema::GRP_TABLE, 'g' )
			               ->where( sprintf( '`g`.`id` = \'%s\'', $request->get( 'id' ) ) )
			               ->fetch( Query::SINGLE );
		}

		return new Response( 'backend/groups/editor.php', [
			'record' => $record
		] );
	}

	public function shortcodesAction() {
		$shortcodes = Query::instance()
		                   ->select()
		                   ->from( Schema::STC_TABLE, 's' )
		                   ->fetch();

		$actions = [
			'-1'     => __( 'Bulk Actions', 'rp-ads-manager' ),
			'delete' => __( 'Delete', 'rp-ads-manager' )
		];

		return new Response( 'backend/shortcodes/list.php', [
			'records' => $shortcodes,
			'actions' => $actions
		] );
	}

	public function shortcodesEditorAction( Request $request ) {
		$record = [
			'id'    => null,
			'title' => ''
		];

		if ( $request->has( 'id' ) ) {
			$record = Query::instance()
			               ->select()
			               ->from( Schema::STC_TABLE, 's' )
			               ->where( sprintf( '`s`.`id` = \'%s\'', $request->get( 'id' ) ) )
			               ->fetch( Query::SINGLE );
		}

		return new Response( 'backend/shortcodes/editor.php', [
			'record' => $record
		] );
	}

	public function settingsAction( Request $request ) {
		// check if the user have submitted the settings
		// wordpress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'rpam_messages', 'rpam_message', __( 'Settings Saved', 'rp-ads-manager' ), 'updated' );
		}

		$tabs = Tabs::instance();
		$tabs->register(
			'main',
			admin_url( 'admin.php?page=rpam-settings' ),
			__( 'Main options', 'rp-ads-manager' ),
			'backend/settings/main.php',
			'__return_empty_array',
			1
		);
		$tabs->register(
			'targetting',
			null,
			__( 'Targeting', 'rp-ads-manager' ) . ' <i>(' . __( 'soon', 'rp-ads-manager' ) . ')</i>',
			'comming_soon.php',
			'__return_empty_array',
			2
		);
		$tabs->register(
			'import|export',
			null,
			__( 'Import/Export', 'rp-ads-manager' ) . ' <i>(' . __( 'soon', 'rp-ads-manager' ) . ')</i>',
			'comming_soon.php',
			'__return_empty_array',
			3
		);
		if ( ! class_exists( Api::class ) ) {
			$tabs->register(
				'remote',
				null,
				__( 'Remote management', 'rp-ads-manager' ) . ' <i>(' . __( 'soon', 'rp-ads-manager' ) . ')</i>',
				'comming_soon.php',
				'__return_empty_array',
				4
			);
		}


		$tabs->activate( $request->get( 'tab' ) ?: 'main' );

		if ( $tabs->active() == 'main' ) {
			wp_enqueue_code_editor( [ 'type' => 'text/html' ] );
			wp_enqueue_script( 'rp-ads-manager-editor', plugins_url( 'rp-ads-manager/assets/settings.js' ) );
		}

		return new Response( $tabs->template(), $tabs->run() );
//
//
//		$active_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
//
//		$return_data = [];
//
//		$tabs = [
//			[
//				'title' => __( 'Main options', 'rp-ads-manager' )
//			],
//			[
//				'title' => __( 'Targeting', 'rp-ads-manager' ) . ' <i>(' . __( 'soon', 'rp-ads-manager' ) . ')</i>'
//			],
//			[
//				'title' => __( 'Import/Export', 'rp-ads-manager' ) . ' <i>(' . __( 'soon', 'rp-ads-manager' ) . ')</i>'
//			],
//			[
//				'title' => __( 'Remote management', 'rp-ads-manager' ) . ' <i>(' . __( 'soon', 'rp-ads-manager' ) . ')</i>'
//			]
//		];
//
//
//		if ( is_null( $active_tab ) ) {
//
//
//			wp_enqueue_code_editor( [ 'type' => 'text/html' ] );
//			wp_enqueue_script( 'rp-ads-manager-editor', plugins_url( 'rp-ads-manager/assets/settings.js' ) );
//
//		} elseif ( 'remote' == $active_tab ) {
//
//			if ( ! class_exists( Api::class ) ) {
//				return [ 'tab' => null ];
//			}
//
//			$crypt = new Encrypt();
//
//			$return_data['connection_data'] = $crypt->encrypt( sprintf(
//				'token=%s&site=%s',
//				AdsManager::instance()->getRemoteToken(),
//				get_option( 'siteurl' )
//			) );
//		}
	}
}