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
class PostNodeEndpoint extends NodeEndpoint {
	
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
		if (!$this->nodeExists( $node)){
			if ($this->giveError){
				return Response::createFromStatus( "node doesn't exist");
			}else{
				return Response::createFromStatus("route not supported");
			}
		}
		$controller = $this->getController( $node);
		$ret = $controller->post( json_decode( $request->getBody(), true), $db);
		return new Response( Status::getStatus("OK"), $ret);
	}
	
}