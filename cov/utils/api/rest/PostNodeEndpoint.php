<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;
use cov\utils\db\DB;

/**
 *
 * @author Ukhando
 *
 */
class PostNodeEndpoint extends NodeEndpoint {
	
	/**
	 *
	 * @param RoutesConfig $routes
	 * @param Nodes $nodes
	 */
	public function __construct( RoutesConfig $routes, Nodes $nodes){
		parent::__construct($routes, $nodes);
	}
	
	
	/**
	 *
	 * {@inheritDoc}
	 * @see Endpoint::main()
	 */
	public function main( Logger $logger, Request $request, DB $db){
		$node = $request->getPath("node");
		$controller = $this->getController( $node);
		if ($controller === null){
			return Response::createFromStatus( "node doesn't exist");
		}else{
			$ret = $controller->post( json_decode( $request->getBody(), true), $db);
			return new Response( Status::getStatus("OK"), $ret);
		}
	}
	
}