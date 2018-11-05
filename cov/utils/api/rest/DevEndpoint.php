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
	 * @var Nodes $nodes
	 */
	private $routes, $nodes;
	
	/**
	 * 
	 * @param RoutesConfig $routes
	 * @param Nodes $nodes
	 */
	public function __construct( RoutesConfig $routes, Nodes $nodes){
		$this->routes = $routes;
		$this->nodes  = $nodes;
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
						"nodes" => $this->nodes
				)
		);
	}
	
}