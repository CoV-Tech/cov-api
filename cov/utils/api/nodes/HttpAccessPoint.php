<?php namespace cov\utils\api\nodes;

use cov\core\debug\Logger;
use cov\utils\api\auth\Authenticator;
use cov\utils\db\DB;
use cov\utils\api\rest\RoutesConfig;
use cov\utils\api\rest\HttpAccessPoint as AccessPoint;

/**
 *
 * @author Ukhando
 *
 */
class HttpAccessPoint {
	
	/**
	 *
	 * @var HttpAccessPoint $accessPoint
	 */
	private $accessPoint;
	
	/**
	 *
	 * @param Logger $logger
	 * @param string $version
	 * @param RoutesConfig $routes
	 * @param Nodes $nodes
	 * @param DB $db
	 * @param Authenticator $auth
	 */
	public function __construct( Logger $logger, string $version, RoutesConfig $routes, Nodes $nodes, DB $db, Authenticator $auth = null){
		$routes->addRoute("GET",  "{node}/{id}",      new GetNodeEndpoint(  $routes, $nodes, false));
		$routes->addRoute("GET",  "{node}",           new GetNodeEndpoint(  $routes, $nodes, false));
		$routes->addRoute("POST", "{node}",           new POSTNodeEndpoint( $routes, $nodes, false));
		$routes->addRoute("GET",  "node/{node}/{id}", new GetNodeEndpoint(  $routes, $nodes));
		$routes->addRoute("GET",  "node/{node}",      new GetNodeEndpoint(  $routes, $nodes));
		$routes->addRoute("POST", "node/{node}",      new POSTNodeEndpoint( $routes, $nodes));
		$routes->addRoute("GET",  "dev",              new DevEndpoint(      $routes, $nodes));
		if ($auth !== null){
            $this->accessPoint = new AccessPoint($logger, $version, $routes, $db, $auth);
        }else{
            $this->accessPoint = new AccessPoint($logger, $version, $routes, $db);
        }
	}
	
	/**
	 *
	 * @return string
	 */
	public function getBaseUrl() : string{
		return $this->accessPoint->getBaseUrl();
	}
	
	/**
	 *
	 */
	public function treatRequest() : void{
		$this->accessPoint->treatRequest();
	}
	
	
	
}