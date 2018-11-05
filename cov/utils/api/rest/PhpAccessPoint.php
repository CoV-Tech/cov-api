<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;
use cov\utils\db\DB;

/**
 * 
 * @author Ukhando
 *
 */
class PhpAccessPoint {
	
	/**
	 * @var RoutesConfig $routes
	 * @var Logger $logger
	 * @var Nodes $nodes
	 * @var DB $db
	 */
	private $routes, $logger, $nodes, $db;
	
	/**
	 * 
	 * @param Logger $logger
	 * @param RoutesConfig $routes
	 * @param Nodes $nodes
	 * @param DB $db
	 */
	public function __construct( Logger $logger, RoutesConfig $routes, Nodes $nodes, DB $db){
		$routes->addRoute("GET", "{node}/{id}", new NodeEndpoint($routes, $nodes));
		$routes->addRoute("GET", "dev", new DevEndpoint( $routes, $nodes));
		$this->routes = $routes;
		$this->logger = $logger;
		$this->nodes  = $nodes;
		$this->db     = $db;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBaseUrl(){
		return $this->routes->getBaseUrl();
	}
	
	/**
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function main( Request $request){
		$route = null;
		try{
			$route = $this->routes->getRoute($this->logger, $request->getUrl(), $request->getMethod());
		}catch( MultipleRoutesException $e){
			$this->logger->write( "multiple routes available for : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("multiple routes");
		}
		if ($route === null){
			$this->logger->write( "route ".$request->getMethod()." ".$request->getUrl()."  not supported", "ERROR");
			return Response::createFromStatus("route not supported");
		}
		if ($route->getEndpoint() === null){
			$this->logger->write( "unknown route error, no endPoint found for : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("unknown route error, no endPoint found");
		}
		$request->parsedUrl( $route->parse( $this->logger, $request->getUrl()));
		$response = null;
		try{
			$response = $route->getEndpoint()->main( $this->logger, $request, $this->db);
		}catch( \Exception $e){
			$response = new Response( new Status(-4, $e->getMessage(), 500), $e->__toString());
		}
		if ($response == null || $response->getStatus() == null){
			$this->logger->write( "unknown route error, no response given for route : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("unknown route error, no response given");
		}
		$this->logger->write( "request succesfull : ".$request->getMethod()." ".$request->getUrl(), "INFO");
		return $response;
	}
}