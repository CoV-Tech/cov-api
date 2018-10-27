<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;

/**
 * 
 * @author Ukhando
 *
 */
class DevEndpoint implements Endpoint {
	
	/**
	 * 
	 * @var RoutesConfig
	 */
	private $routes;
	
	/**
	 * 
	 * @param RoutesConfig $routes
	 */
	public function __construct( RoutesConfig $routes){
		$this->routes = $routes;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \cov\utils\api\rest\Endpoint::main()
	 */
	public function main( Logger $logger, Request $request){
		
		return new Response(Status::getStatus("OK"), $this->routes->getAllRoutes());
	}
	
}