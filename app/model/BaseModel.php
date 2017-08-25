<?php

namespace Model;

class BaseModel {

	const HOST='localhost';
	const PORT='3306';
	const DBNAME='varunshr_bma';
	const USER='root';
	const PASSWORD='';
	const DB_CONN_STRING='mysql:host=' . self::HOST . ';port=' . self::PORT . ';dbname=' . self::DBNAME;

	protected $f3;
	protected $db;

	public function __construct() {
		$this->f3 = \Base::instance();
		$this->db = new \DB\SQL(
		    self::DB_CONN_STRING,
		    self::USER,
		    self::PASSWORD,
		    array( \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION )
		);
	}
}