<?php

namespace RP\AdsManager\Database;

use RP\AdsManager\Util\Singleton;

/**
 * @author Sadicov Vladimir <sadikoff@gmail.com>
 */
class Connection extends Singleton {

	/**
	 * @var \wpdb
	 */
	private $wpdb;
	private $schema;

	protected function __construct() {
		global $wpdb;

		$this->wpdb   = $wpdb;
		$this->schema = new Schema( $wpdb->prefix );
	}

	public function error() {
		return $this->wpdb;
	}

	public function table( $_table ) {
		return $this->schema->validateTable( $_table );
	}

	public function replace( $table, $data ) {
		$this->wpdb->replace( $this->schema->validateTable( $table ), $data );
	}

	public function update( $table, $data, $condition ) {
		$this->wpdb->update( $this->schema->validateTable( $table ), $data, $condition );
	}

	public function delete( $table, $condition ) {
		$this->wpdb->delete( $this->schema->validateTable( $table ), $condition );
	}

	public function insert( $table, $data ) {
		$this->wpdb->insert( $this->schema->validateTable( $table ), $data );

		return $this->wpdb->insert_id;
	}

	public function execute( $query ) {
		$this->wpdb->query( $query );

		return $this->wpdb->last_result;
	}

	public function validate() {
		if ( ! $this->schema->isValid() ) {
			$this->schema->upgrade();
		}
	}
}
