<?php

namespace RP\AdsManager\Database;

use RP\AdsManager\Util\Singleton;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
final class Query extends Singleton {
	const MULTIPLE = 1;
	const SINGLE = 2;

	private $_connection;

	private $Q_select = '';
	private $Q_from = null;
	private $Q_join = [];
	private $Q_where = '';

	protected function __construct() {
		$this->_connection = Connection::instance();
	}

	public function error() {
		return $this->_connection->error();
	}

	public function insert( $_table, $data ) {
		return $this->_connection->insert( $_table, $data );
	}

	public function replace( $_table, $data ) {
		$this->_connection->replace( $_table, $data );
	}

	public function update( $_table, $data, $condition ) {
		$this->_connection->update( $_table, $data, $condition );
	}

	public function delete( $_table, $condition ) {
		$this->_connection->delete( $_table, $condition );
	}

	public function select( $_select = '*' ) {
		$this->Q_select = $_select;

		return $this;
	}

	public function from( $_table, $_alias ) {
		$table = $this->_connection->table( $_table );

		$this->Q_from = [ $table, $_alias ];

		return $this;
	}

	public function join( $_table, $_alias, $_condition ) {
		$table = $this->_connection->table( $_table );

		$this->Q_join[] = [ $table, $_alias, $_condition ];

		return $this;
	}

	public function where( $_condition ) {
		$this->Q_where = $_condition;

		return $this;
	}

	public function fetch( $returnType = Query::MULTIPLE ) {
		$queryResult = $this->_connection->execute( (string) $this );

		$this->reset();

		return $this->normalize( $queryResult, $returnType );
	}

	private function reset() {
		$this->Q_select = '';
		$this->Q_from   = null;
		$this->Q_join   = [];
		$this->Q_where  = '';
	}

	private function normalize( $queryResult, $returnType ) {
		$result = [];
		foreach ( $queryResult as $object ) {
			$data = \get_object_vars( $object );
			if ( Query::SINGLE === $returnType ) {
				$result = $data;
				break;
			}

			$result[] = $data;
		}

		return $result;
	}

	public function __toString() {
		$query = /** @lang text */
			'SELECT %s FROM `%s` AS `%s`';

		$query = sprintf( $query, $this->Q_select, $this->Q_from[0], $this->Q_from[1] );

		if ( ! empty( $this->Q_join ) ) {
			foreach ( $this->Q_join as $join ) {
				$query .= sprintf( ' LEFT JOIN `%s` AS `%s` ON %s', $join[0], $join[1], $join[2] );
			}
		}

		if ( $this->Q_where ) {
			$query .= sprintf( ' WHERE %s', $this->Q_where );
		}

		return $query;
	}

//	/**
//	 * @param integer $place_id
//	 * @param integer|bool $object_id
//	 *
//	 * @return array
//	 */
//	public function codesByPlace( $place_id, $object_id = false ) {
//		$objectPosition = $object_id ? " AND `r`.`options` LIKE '%:\"" . $object_id . "\"%'" : '';
//
//		$records = $this->wpdb->get_results(
//			"SELECT `a`.*, `r`.`options`, `r`.`ple_id`
//			   FROM {$this->ads_table_name} AS `a`
//			   LEFT JOIN {$this->rel_table_name} AS `r` ON `r`.`ads_id` = `a`.`id`
//			   WHERE `r`.`ple_id` = '{$place}'{$objectPosition} AND `a`.`enabled` = 1",
//			ARRAY_A
//		);
//
//		return [];
//	}
}