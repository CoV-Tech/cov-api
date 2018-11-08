<?php namespace cov\utils\api\nodes;

use cov\core\debug\Logger;
use cov\utils\db\DB;
use cov\utils\api\rest\RoutesConfig;
use cov\utils\api\rest\Response;
use cov\utils\api\rest\Status;
use cov\utils\api\rest\Request;
use cov\utils\api\rest\Endpoint;

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
						"nodes"  => $this->nodes,
						"types"  => array(
								"routes",
								"nodes"
						)
				)
		);
	}
	
}