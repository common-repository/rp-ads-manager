<?php

namespace RP\AdsManager\Database;

use RP\AdsManager\WP\Option;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
final class Schema {
	const VERSION = '3.4';

	const TMPL_CREATE_TABLE = /** @lang text */
		"CREATE TABLE %s (\n%s\n);";
	const TMPL_FIELDS = '`%s` %s';
	const TMPL_PRIMARY_KEY = 'PRIMARY KEY (%s)';
	const TMPL_UNIQUE = 'UNIQUE KEY `%s` (`%s`)';

	const CDS_TABLE = 'rpam_codes';
	const GRP_TABLE = 'rpam_groups';
	const OPT_TABLE = 'rpam_options';
	const STC_TABLE = 'rpam_shortcodes';
	const STS_TABLE = 'rpam_statistics';

	private $prefix;

	private $_mapping = [
		self::CDS_TABLE => [
			'fields'  => [
				'id'       => 'mediumint(9) NOT NULL AUTO_INCREMENT',
				'group_id' => 'mediumint(9) NULL DEFAULT NULL',
				'title'    => 'varchar(32) NOT NULL',
				'code'     => 'longtext NOT NULL',
				'init'     => 'longtext NULL DEFAULT NULL',
				'enabled'  => 'tinyint(1) NOT NULL DEFAULT \'1\''
			],
			'primary' => 'id'
		],
		self::GRP_TABLE => [
			'fields'  => [
				'id'    => 'mediumint(9) NOT NULL AUTO_INCREMENT',
				'title' => 'varchar(32) NOT NULL',
			],
			'primary' => 'id',
			'unique'  => 'title'
		],
		self::STC_TABLE => [
			'fields'  => [
				'id'    => 'mediumint(9) NOT NULL AUTO_INCREMENT',
				'title' => 'varchar(32) NOT NULL',
			],
			'primary' => 'id',
			'unique'  => 'title'
		],
		self::OPT_TABLE => [
			'fields'  => [
				'code_id'  => 'mediumint(9) NOT NULL',
				'place_id' => 'mediumint(9) NOT NULL',
				'options'  => 'longtext NOT NULL'
			],
			'primary' => 'code_id, place_id'
		]
	];

	private $_version = null;

	public function __construct( $prefix ) {
		$this->prefix = $prefix;

		$this->_version = \get_option( Option::DB_VERSION, null );
	}

	public function validateTable( $tableName ) {
		if ( ! array_key_exists( $tableName, $this->_mapping ) ) {
			trigger_error( 'Unknown table' );
		}

		return sprintf( '%s%s', $this->prefix, $tableName );
	}

	public function upgrade() {
		$this->preUpgrade();

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $this->getSQL() );

		$this->postUpgrade();

		$this->updateVersion();
	}

	private function getSQL() {
		$sql = '';

		foreach ( $this->_mapping as $tableName => $options ) {
			$fields = [];

			foreach ( $options['fields'] as $field => $option ) {
				$fields[] = sprintf( self::TMPL_FIELDS, $field, $option );
			}

			if ( array_key_exists( 'primary', $options ) ) {
				$fields[] = sprintf( self::TMPL_PRIMARY_KEY, $options['primary'] );
			}

			if ( array_key_exists( 'unique', $options ) ) {
				$fields[] = sprintf( self::TMPL_UNIQUE, $tableName . '_' . $options['unique'], $options['unique'] );
			}

			$sql .= sprintf(
				self::TMPL_CREATE_TABLE,
				$this->prefix . $tableName,
				implode( ",\n", $fields )
			);
		}

		return $sql;
	}

	private function preUpgrade() {
		if ( null === $this->_version ) {
			return;
		}
	}

	private function postUpgrade() {
		if ( null === $this->_version ) {
			return;
		}

		global $wpdb;

		if ( $this->versionIsLower( '2.6' ) ) {
			$wpdb->query( "ALTER TABLE `{$this->prefix}rpam_ads` DROP `type`;" );
		}

		if ( $this->versionIsLower( '3.0' ) ) {
			$records = $wpdb->get_results( "SELECT * FROM `{$this->prefix}rpam_ads`", \ARRAY_A );
			foreach ( $records as $record ) {
				$new_data = [
					'id'      => $record['id'],
					'title'   => $record['title'],
					'enabled' => $record['enabled']
				];

				$code = stripcslashes( unserialize( $record['code'] ) );

				$new_data['code'] = $code;

				$init = unserialize( $record['init'] );
				if ( is_array( $init ) ) {

				} else {
					$init = stripcslashes( $init );
				}

				$new_data['init'] = $init;

				if ( 'none' !== $record['folder'] ) {
					$groupTable = $wpdb->prefix . self::GRP_TABLE;
					$group      = $wpdb->get_row( "SELECT * FROM `{$groupTable}` WHERE `title` = '{$record['folder']}'", \ARRAY_A );

					$new_data['group_id'] = null === $group ? $wpdb->insert( $groupTable, [ 'title' => $record['folder'] ] ) : $group['id'];
				}

				$wpdb->insert( $this->prefix . self::CDS_TABLE, $new_data );
			}

			$records = $wpdb->get_results( "SELECT * FROM `{$this->prefix}rpam_rel`", \ARRAY_A );
			foreach ( $records as $record ) {
				$new_data = [
					'code_id'  => $record['ads_id'],
					'place_id' => $record['ple_id'],
					'options'  => $record['options']
				];

				$wpdb->insert( $this->prefix . self::OPT_TABLE, $new_data );
			}

			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rpam_ads" );
			$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rpam_rel" );
		}

		if ( $this->versionIsLower( '3.3' ) ) {
			$wpdb->update( $wpdb->prefix . self::CDS_TABLE, [ 'group_id' => null ], [ 'group_id' => 0 ] );
			$wpdb->update( $wpdb->prefix . self::CDS_TABLE, [ 'init' => null ], [ 'init' => '' ] );
		}
	}

	public function isValid() {
		return $this->versionIsEqual( self::VERSION );
	}

	private function versionIsEqual( $version ) {
		return version_compare( $this->_version, $version, '==' );
	}

	private function versionIsLower( $version ) {
		return version_compare( $this->_version, $version, '<' );
	}

	private function updateVersion() {
		$this->_version = self::VERSION;
		\update_option( Option::DB_VERSION, self::VERSION );
	}
}