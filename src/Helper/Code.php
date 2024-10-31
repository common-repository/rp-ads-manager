<?php

namespace RP\AdsManager\Helper;

use RP\AdsManager\Database\Helper;
use RP\AdsManager\Database\Query;
use RP\AdsManager\Database\Schema;
use RP\AdsManager\Util\Request;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Code extends Helper {
	private function processCode( $data ) {
		$newData = [];

		foreach ( $data as $key => $value ) {
			switch ( $key ) {
				case 'title':
					$newData['title'] = stripcslashes( htmlspecialchars( $value ) );
					break;
				case 'code':
				case 'init':
					$newData[ $key ] = stripcslashes( $value );
					break;
				case 'enabled':
					$newData['enabled'] = (int) $value;
					break;
				case 'group':
					if ( $value != '' ) {
						$group = $this->query
							->select()
							->from( Schema::GRP_TABLE, 'g' )
							->where( sprintf( '`g`.`title` LIKE \'%s\'', $value ) )
							->fetch( Query::SINGLE );

						if ( $group ) {
							$newData['group_id'] = $group['id'];
						} else {
							$newData['group_id'] = $this->query->insert( Schema::GRP_TABLE, [
								'id'    => null,
								'title' => $value
							] );
						}
					} else {
						$newData['group_id'] = null;
					}
					break;
			}
		}

		return $newData;
	}

	private function processPlaces( $data ) {
		$processed = [];

		if ( array_key_exists( 'places', $data ) ) {
			foreach ( $data['places'] as $slug => $options ) {
				$processed[] = [
					'place_id' => wpView::instance()->idFromSlug( $slug ),
					'options'  => serialize( $options ),
				];
			}
		}

		return $processed;
	}

	/**
	 * @param Request $request
	 *
	 * @return int
	 */
	public function create( $request ) {
		$data = $request->get( 'block', Request::POST );

		$newCode = array_merge( [ 'id' => null ], $this->processCode( $data ) );

		$insertId = $this->query->insert( Schema::CDS_TABLE, $newCode );

		$newPlaces = $this->processPlaces( $data );

		foreach ( $newPlaces as $place ) {
			$this->query->insert( Schema::OPT_TABLE, array_merge( [ 'code_id' => $insertId ], $place ) );
		}

		set_transient( 'rpam_notice', __( 'Successfully created', 'rp-ads-manager' ), 2 );

		return $insertId;
	}

	public function update( $data, $id, $woPlaces = false ) {
		if ( $data instanceof Request ) {
			$request = $data;
			$data    = $request->get( 'block', Request::POST );
		}

		$newCode = $this->processCode( $data );

		$oldCode = $this->query
			->select( '`c`.`title`, `c`.`code`, `c`.`init`, `c`.`group_id`, `c`.`enabled`' )
			->from( Schema::CDS_TABLE, 'c' )
			->where( sprintf( '`c`.`id` = %s', $id ) )
			->fetch( Query::SINGLE );

		if ( ! is_null( $oldCode['init'] ) && ! array_key_exists( 'init', $newCode ) ) {
			$newCode['init'] = null;
		}

		$diff = array_diff_assoc( $newCode, $oldCode );

		if ( ! empty( $diff ) ) {
			$this->query->update( Schema::CDS_TABLE, $diff, [ 'id' => $id ] );
		}

		if ( ! $woPlaces ) {
			$newPlaces = $this->processPlaces( $data );

			$oldPlaces = $this->query
				->select( '`o`.`place_id`, `o`.`options`' )
				->from( Schema::OPT_TABLE, 'o' )
				->where( sprintf( '`o`.`code_id` = %s', $id ) )
				->fetch();

			$delPlaces = array_udiff( $oldPlaces, $newPlaces, function ( $a, $b ) {
				return $a['place_id'] - $b['place_id'];
			} );

			$newPlaces = array_udiff( $newPlaces, $delPlaces, function ( $a, $b ) {
				return $a['place_id'] - $b['place_id'];
			} );

			if ( ! empty( $delPlaces ) ) {
				foreach ( $delPlaces as $place ) {
					$this->query->delete( Schema::OPT_TABLE, [ 'code_id' => $id, 'place_id' => $place['place_id'] ] );
				}
			}

			$updOrInsPlaces = array_udiff( $newPlaces, $oldPlaces, function ( $a, $b ) {
				return strcasecmp( serialize( $a ), serialize( $b ) );
			} );

			foreach ( $updOrInsPlaces as $place ) {
				$this->query->replace( Schema::OPT_TABLE, array_merge( [ 'code_id' => $id ], $place ) );
			}
		}
		set_transient( 'rpam_notice', __( 'Successfully updated', 'rp-ads-manager' ), 2 );
	}

	public function activate( $ids ) {
		foreach ( $ids as $id ) {
			$this->update( [ 'enabled' => 1 ], $id, true );
		}

		set_transient( 'rpam_notice', __( 'Successfully activated', 'rp-ads-manager' ), 2 );
	}

	public function deactivate( $ids ) {
		foreach ( $ids as $id ) {
			$this->update( [ 'enabled' => 0 ], $id, true );
		}

		set_transient( 'rpam_notice', __( 'Successfully deactivated', 'rp-ads-manager' ), 2 );
	}

	public function delete( $ids ) {
		foreach ( $ids as $id ) {
			$this->query->delete( Schema::CDS_TABLE, [ 'id' => $id ] );
			$this->query->delete( Schema::OPT_TABLE, [ 'code_id' => $id ] );
		}

		set_transient( 'rpam_notice', __( 'Successfully deleted', 'rp-ads-manager' ), 2 );
	}

	public function byPlaceId( $place_id, $widget_id = false, $enabled = true ) {
		if ($widget_id) {
			$length = mb_strlen($widget_id, 'utf-8');
			$placePosition = sprintf(' AND `o`.`options` LIKE \'%%s:%s:"%s"%%\'', $length, $widget_id);
		} else {
			$placePosition = '';
		}

		if ($enabled) {
			$enabled = ' AND `c`.`enabled` = 1';
		}

		$records = $this->query
			->select()
			->from( Schema::CDS_TABLE, 'c' )
			->join( Schema::OPT_TABLE, 'o', '`c`.`id` = `o`.`code_id`' )
			->where( sprintf( '`o`.`place_id` = \'%s\'%s%s', $place_id, $placePosition, $enabled ) )
			->fetch();

		return $records;
	}
}