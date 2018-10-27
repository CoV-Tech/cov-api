<?php namespace cov\utils\db;

/**
 * 
 * @author Ukhando
 *
 */
class DBHost {
	/**
	 * 
	 * @var string $username
	 * @var string $password
	 * @var string $host
	 * @var string $dbname
	 * @var string $driver
	 * @var string $key
	 */
	private $username, $password, $host, $dbname, $driver, $key = null;
	
	/**
	 * 
	 * @param string $username
	 * @param string $password
	 * @param string $host
	 * @param string $dbname
	 * @param string $driver
	 * @throws DBInvalidDriverException
	 */
	public function __construct( string $username, string $password, string $host, string $dbname, string $driver = "mysql"){
		$this->username = $username;
		$this->password = $password;
		$this->host     = $host;
		$this->dbname   = $dbname;
		$this->driver   = $driver;
		if (!Self::driverIsSupported( $driver)){
			throw new DBInvalidDriverException();
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	public function toKey(){
		if ($this->key === null){
			$this->key = hash("sha1", $this->username.$this->password.$this->host.$this->dbname.$this->driver, false);
		}
		return $this->key;
	}
	
	/**
	 * 
	 * @throws DBInvalidDriverException
	 * @return string
	 */
	public function toPDO(){
		switch ($this->driver){
			case "mysql":
				return "mysql:host=".$this->host.";dbname=".$this->dbname;
			case "oci":
				return "oci:dbname=//".$this->host."/".$this->dbname;
			default:
				throw new DBInvalidDriverException();
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getPassword(){
		return $this->password;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getUsername(){
		return $this->username;
	}
	
	/**
	 * 
	 * @param string $driver
	 * @return boolean
	 */
	public static function driverIsSupported( string $driver){
		switch ($driver){
			case "mysql":
				return true;
			case "oci":
				return true;
			default:
				return false;
		}
	}
	
}