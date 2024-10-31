<?php

namespace RP\AdsManager\Util;

use RP\AdsManager\Helper\wpView;

final class Converter {
	const CODE_VIEW_LIST = 0x001;
	const CODE_JSON_LIST = 0x002;
	const CODE_VIEW_SINGLE = 0x003;
	const GROUP_VIEW_LIST = 0x011;
	const GROUP_JSON_LIST = 0x012;

	public static function run( $type, $data ) {
		$convertedData = [];

		switch ( $type ) {
			case self::CODE_VIEW_LIST:
				foreach ( $data as $array ) {
					$id    = (int) $array['id'];
					$place = wpView::instance()->getSlug( $array['place_id'], true );

					if ( array_key_exists( $id, $convertedData ) ) {
						$convertedData[ $id ]['places'][] = [
							'title'   => $place,
							'options' => unserialize( $array['options'] )
						];
					} else {
						$convertedData[ $id ] = [
							'title'   => $array['title'],
							'enabled' => (bool) $array['enabled'],
							'group'   => $array['group'],
							'places'  => [],
						];

						if ( $place ) {
							$convertedData[ $id ]['places'][] = [
								'title'   => $place,
								'options' => unserialize( $array['options'] )
							];
						}
					}
				}
				break;
			case self::CODE_VIEW_SINGLE:
				foreach ( $data as $array ) {
					$id    = (int) $array['id'];
					$place = wpView::instance()->getSlug( $array['place_id'] );

					if ( array_key_exists( 0, $convertedData ) ) {
						$convertedData[0]['places'][ $place ] = unserialize( $array['options'] );
					} else {
						$convertedData[0] = [
							'id'      => $id,
							'title'   => $array['title'],
							'code'    => $array['code'],
							'init'    => $array['init'],
							'enabled' => (bool) $array['enabled'],
							'group'   => $array['group'],
							'places'  => [],
						];

						if ( $place ) {
							$convertedData[0]['places'][ $place ] = unserialize( $array['options'] );
						}
					}
				}

				$convertedData = ! empty( $convertedData ) ? $convertedData[0] : null;

				break;
			case self::CODE_JSON_LIST:
				foreach ( $data as $array ) {
					$id    = (int) $array['id'];
					$place = wpView::instance()->getSlug( $array['place_id'] );

					if ( array_key_exists( $id, $convertedData ) ) {
						$convertedData[ $id ]['places'][ $place ] = unserialize( $array['options'] );
					} else {
						$convertedData[ $id ] = [
							'id'       => $id,
							'title'    => $array['title'],
							'code'     => $array['code'],
							'use_init' => ! is_null( $array['init'] ),
							'init'     => $array['init'],
							'enabled'  => (bool) $array['enabled'],
							'group'    => $array['group'] ?: 'none',
							'places'   => [],
						];

						if ( $place ) {
							$convertedData[ $id ]['places'][ $place ] = unserialize( $array['options'] );
						}
					}
				}

				break;
		}

		return $convertedData;
	}
}
