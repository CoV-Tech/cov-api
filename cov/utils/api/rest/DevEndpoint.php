<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;
use cov\utils\db\DB;

/**
 * 
 * @author Ukhando
 *
 */
class DevEndpoint implements Endpoint {
	
	/**
	 * 
	 * @var RoutesConfig $routes
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
	public function main( Logger $logger, Request $request, DB $db){
		
		return new Response(
				Status::getStatus("OK"), 
				array(
						"routes" => $this->routes->getAllRoutes(),
						"types"  => array(
								"routes"
						)
				)
		);
	}
	
}