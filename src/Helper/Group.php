<?php

namespace RP\AdsManager\Helper;

use RP\AdsManager\Database\Helper;
use RP\AdsManager\Database\Query;
use RP\AdsManager\Database\Schema;
use RP\AdsManager\Util\Request;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Group extends Helper {

	private function clean( $data ) {
		if ( array_key_exists( 'title', $data ) ) {
			$data['title'] = stripcslashes( htmlspecialchars( $data['title'] ) );
		}

		return $data;
	}

	/**
	 * @param Request $request
	 *
	 * @return int
	 */
	public function create( $request ) {
		$data = $request->get( 'group', Request::POST );
		$data = $this->clean( $data );

		set_transient( 'rpam_notice', __( 'Successfully created', 'rp-ads-manager' ), 2 );

		return $this->query->insert( Schema::GRP_TABLE, $data );
	}

	public function update( $data, $id ) {
		if ( $data instanceof Request ) {
			$request = $data;

			$data = $request->get( 'group', Request::POST );
		}

		$data = $this->clean( $data );

		$oldGroup = $this->query
			->select( '`g`.`title`' )
			->from( Schema::GRP_TABLE, 'g' )
			->where( sprintf( '`g`.`id` = %s', $id ) )
			->fetch( Query::SINGLE );

		$diff = array_diff( $data, $oldGroup );

		if ( ! empty( $diff ) ) {
			$this->query->update( Schema::GRP_TABLE, $diff, [ 'id' => $id ] );
		}

		set_transient( 'rpam_notice', __( 'Successfully updated', 'rp-ads-manager' ), 2 );
	}

	public function delete( $ids ) {
		foreach ( $ids as $id ) {
			$this->query->delete( Schema::GRP_TABLE, [ 'id' => $id ] );
			$this->query->update( Schema::CDS_TABLE, [ 'group_id' => null ], [ 'group_id' => $id ] );
		}

		set_transient( 'rpam_notice', __( 'Successfully deleted', 'rp-ads-manager' ), 2 );
	}
}