<?php

namespace RP\AdsManager\Helper;

use RP\AdsManager\Database\Helper;
use RP\AdsManager\Database\Query;
use RP\AdsManager\Database\Schema;
use RP\AdsManager\Util\Request;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Shortcode extends Helper {

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
		$data = $request->get( 'shortcode', Request::POST );
		$data = $this->clean( $data );

		set_transient( 'rpam_notice', __( 'Successfully created', 'rp-ads-manager' ), 2 );

		return $this->query->insert( Schema::STC_TABLE, $data );
	}

	public function update( $data, $id ) {
		if ( $data instanceof Request ) {
			$request = $data;

			$data = $request->get( 'group', Request::POST );
		}

		$this->clean( $data );

		$oldShortcode = $this->query
			->select( '`s`.`title`' )
			->from( Schema::STC_TABLE, 's' )
			->where( sprintf( '`s`.`id` = %s', $id ) )
			->fetch( Query::SINGLE );

		$diff = array_diff( $data, $oldShortcode );

		if ( ! empty( $diff ) ) {
			$this->query->update( Schema::STC_TABLE, $diff, [ 'id' => $id ] );
		}

		set_transient( 'rpam_notice', __( 'Successfully updated', 'rp-ads-manager' ), 2 );
	}

	public function delete( $ids ) {
		foreach ( $ids as $id ) {
			$this->query->delete( Schema::STC_TABLE, [ 'id' => $id ] );
			$codes = Code::instance()->byPlaceId( wpView::SHORTCODE, $id, false );
			foreach ( $codes as $code ) {
				$this->query->delete( Schema::OPT_TABLE, [
					'code_id'  => $code['id'],
					'place_id' => wpView::SHORTCODE
				] );
			}
		}

		set_transient( 'rpam_notice', __( 'Successfully deleted', 'rp-ads-manager' ), 2 );
	}
}