<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;
use cov\utils\db\DB;

/**
 *
 * @author Ukhando
 *
 */
class GetNodeEndpoint extends NodeEndpoint {
	
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
		$id = $request->getPath( "id");
		$controller = $this->getController( $node);
		if ($controller === null){
			return Response::createFromStatus( "node doesn't exist");
		}else{
			$fields = $request->getFields();
			if (count($fields->getSubFields()) < 1){
				$fields = $this->getDefaultFields( $node);
			}
			$nodeObject = null;
			if ($id !== null){
				$nodeObject = $controller->get( $id, $fields, $db);
			}else{
				$nodeObject = $controller->getAll($fields, $db);
			}
			return new Response( Status::getStatus("OK"), $nodeObject);
		}
	}
	
}