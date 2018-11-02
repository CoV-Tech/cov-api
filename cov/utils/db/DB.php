<?php namespace cov\utils\db;

use cov\core\exceptions\CovException;

/**
 * 
 * @author Ukhando
 *
 */
class DB{
    
    /**
     * 
     * @var DBHost[] $hosts
     * @var DBHost $lastHost
     */
    private $hosts, $lastHost;
    
    /**
     * 
     */
    public function __construct(){
        $this->hosts = array();
    }
    
    /**
     * 
     * @param string $name
     * @param string $host
     * @param string $dbname
     * @param string $username
     * @param string $password
     * @param string $mode
     * @param string $driver
     * @throws DBInvalidDriverException
     * @throws CovException
     */
    public function addHost( string $name, string $host, string $dbname, string $username, string $password, string $mode = "w", string $driver = "mysql"){
        if (!DBHost::driverIsSupported($driver)){
            throw new DBInvalidDriverException();
        }
        if ($mode !== "r" && $mode !== "w"){
            throw new CovException("mode must be 'r' or 'w', ".$mode." given");
        }
        $host = new DBHost($username, $password, $host, $dbname, $driver);
        if (!isset($this->hosts[$name])){
            $this->hosts[$name] = array();
            $this->hosts[$name]["r"] = null;
            $this->hosts[$name]["w"] = null;
        }
        $this->hosts[$name][$mode] = $host;
    }
    
    /**
     * 
     * @param string $name
     * @return NULL|DBHost[]
     */
    private function getHosts( string $name){
        return isset($this->hosts[$name]) ? $this->hosts[$name] : null;
    }
    
    /**
     * 
     * @param string $name
     * @param string $mode
     * @throws CovException
     * @return NULL|DBHost
     */
    private function getHost( string $name, string $mode = "w"){
        if ($mode !== "r" && $mode !== "w"){
            throw new CovException("mode must be 'r' or 'w', ".$mode." given");
        }
        $hosts = $this->getHosts($name);
        if ($hosts == null){
            return null;
        }
        if ($mode == "r" && $hosts["r"] == null){
            $mode = "w";
        }
        return $hosts[$mode];
    }
    
    /**
     * 
     * @param string $query
     * @throws CovException
     * @return string
     */
    private static function isReadOrWrite( string $query){
        $reads = array( "SELECT");
        $writes = array( "INSERT", "DELETE", "UPDATE", "CREATE", "DROP", "ALTER", "ADD", "SELECT INTO");
        foreach( $writes as $write){
            if (strtoupper(substr($query, 0, strlen($write))) == strtoupper($write)){
                return "w";
            }
        }
        foreach( $reads as $read){
            if (strtoupper(substr($query, 0, strlen($read))) == strtoupper($read)){
                return "r";
            }
        }
        throw new CovException("SQL method not reconized");
    }
    
    
    /**
     * 
     * @param string $host
     * @param string $query
     * @param array $parameters
     * @throws CovException
     * @return boolean
     */
    public function executeQuery( string $host, string $query, array $parameters = []){
        if (preg_match("/^[^;]*$/", $query)){
            throw new CovException("Illegal characters");
        }
        $mode = self::isReadOrWrite( $query);
        $DBHost = $this->getHost( $host, $mode);
        if ($DBHost == null){
            throw new CovException("host ".$host." not known");
        }
        $this->lastHost = $DBHost;
        return DBConnection::executeQuery( $DBHost, $query, $parameters);
    }
    
    /**
     * 
     * @throws CovException
     * @return array
     */
    public function getResults(){
        if ($this->lastHost == null){
            throw new CovException("no query has yet run");
        }
        return DBConnection::getResults($this->lastHost);
    }
    
    
}
