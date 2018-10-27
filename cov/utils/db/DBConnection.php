<?php namespace cov\utils\db;

use \PDO as PDO;
use \PDOStatement as PDOStatement;

/**
 * 
 * @author Ukhando
 *
 */
class DBConnection extends PDO {
	
	/**
	 * @var PDOStatement $stmt
	 */
	private $stmt = null;
	
	/**
	 * @param DBHost $host
	 */
	private function __construct( DBHost $host){
		parent::__construct( $host->toPDO(), $host->getUsername(), $host->getPassword());
		$this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	/**
	 * @param string $query This must be a valid SQL statement template for the target database server.
	 * @param array $parameters
	 * @return bool if the execution was succesfull
	 */
	private function executeTheQuery( string $query, array $parameters = []){
		$this->stmt=parent::prepare( $query);
		foreach ($parameters as $name => $value){
			$this->stmt->bindValue( $name, $value[0], $value[1]);
		}
		return $this->stmt->execute();
	}
	
	/**
	 * @return DBConnection
	 */
	public function __clone(){
		return $this;
	}
	
	
	/**
	 * Returns an array containing all of the result set rows
	 * @return array SQLConnection::getTheResults returns an array containing all of the remaining rows in the result set. The array represents each row as either an array of column values or an object with properties corresponding to each column name. An empty array is returned if there are zero results to fetch, or false on failure.<br><br>Using this method to fetch large result sets will result in a heavy demand on system and possibly network resources. Rather than retrieving all of the data and manipulating it in PHP, consider using the database server to manipulate the result sets. For example, use the WHERE and ORDER BY clauses in SQL to restrict results before retrieving and processing them with PHP.
	 */
	private function getTheResults( ){
		return $this->stmt == null ? array() : $this->stmt->fetchall();
	}
	
	/**
	 * @var DBConnection[] $connection
	 */
	private static $connection = array();
	
	/**
	 * @return DBConnection
	 */
	public static function getConnection( DBHost $host){
		if (!isset(Self::$connection[$host->toKey()])){
			Self::$connection[$host->toKey()] = new DBConnection($host);
		}
		return Self::$connection[$host->toKey()];
	}
	
	/**
	 * Prepares a statement for execution<br>
	 * Binds the values to the parameters<br>
	 * And executes the statement
	 * @param DBHost $host
	 * @param string $query This must be a valid SQL statement template for the target database server.
	 * @param array $parameters the parameters
	 * @return bool true on success or false on failure
	 */
	public static function executeQuery( DBHost $host, string $query, array $parameters = []){
		return Self::getConnection($host)->executeTheQuery( $query, $parameters);
	}
	
	/**
	 * Returns an array containing all of the result set rows
	 * @return array getResults returns an array containing all of the remaining rows in the result set. The array represents each row as either an array of column values or an object with properties corresponding to each column name. An empty array is returned if there are zero results to fetch, or false on failure.<br><br>Using this method to fetch large result sets will result in a heavy demand on system and possibly network resources. Rather than retrieving all of the data and manipulating it in PHP, consider using the database server to manipulate the result sets. For example, use the WHERE and ORDER BY clauses in SQL to restrict results before retrieving and processing them with PHP.
	 */
	public static function getResults( DBHost $host){
		return Self::getConnection($host)->getTheResults();
	}
	
}