<?php namespace cov\utils\api\nodes;

use cov\core\debug\Logger;
use cov\utils\db\DB;
use cov\utils\api\rest\RoutesConfig;
use cov\utils\api\rest\Request;
use cov\utils\api\rest\Response;
use cov\utils\api\rest\Status;

/**
 *
 * @author Ukhando
 *
 */
class GetNodeEndpoint extends NodeEndpoint {
	
	private $giveError;
	
	/**
	 * 
	 * @param RoutesConfig $routes
	 * @param Nodes $nodes
	 */
	public function __construct( RoutesConfig $routes, Nodes $nodes, bool $giveError = true){
		parent::__construct($routes, $nodes);
		$this->giveError = $giveError;
	}
	
	
	/**
	 *
	 * {@inheritDoc}
	 * @see Endpoint::main()
	 */
	public function main( Logger $logger, Request $request, DB $db){
		$node = $request->getPath("node");
		$id = $request->getPath( "id");
		if (!$this->nodeExists( $node)){
			if ($this->giveError){
				return Response::createFromStatus( "node doesn't exist");
			}else{
				return Response::createFromStatus("route not supported");
			}
		}
		$controller = $this->getController( $node);
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