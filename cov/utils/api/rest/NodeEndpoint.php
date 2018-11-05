<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;
use cov\utils\db\DB;

/**
 *
 * @author Ukhando
 *
 */
class NodeEndpoint implements Endpoint {
	
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
	 * @see Endpoint::main()
	 */
	public function main( Logger $logger, Request $request, DB $db){
		$node = $request->getPath("node");
		$controller = $this->nodes->getController( $node);
		if ($controller === null){
			return Response::createFromStatus( "node doesn't exist");
		}else{
			if ($request->getMethod() == "GET"){
				$fields = $request->getFields();
				if (count($fields->getSubFields()) < 1){
					$fields = $this->nodes->getDefaultFields( $node);
				}
				$tool = $controller->get( $request->getPath("id"), $fields, $db);
				return new Response( Status::getStatus("OK"), $tool);
			}elseif ($request->getMethod() == "POST"){
				
			}
		}
		
	}
	
}