<?php namespace cov\utils\api\nodes;

use cov\utils\api\rest\RoutesConfig;
use cov\utils\api\rest\Endpoint;
use cov\utils\api\rest\Field;

/**
 *
 * @author Ukhando
 *
 */
abstract class NodeEndpoint implements Endpoint {
	
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
	 * @param string $node
	 * @return bool
	 */
	public function nodeExists( string $node) : bool{
		return $this->nodes->nodeExists( $node);
	}
	
	/**
	 * 
	 * @param string $node
	 * @return NodeController
	 */
	public function getController( string $node) : NodeController{
		return $this->nodes->getController($node);
	}
	
	/**
	 * 
	 * @param string $node
	 * @return Field
	 */
	public function getDefaultFields( string $node) : Field{
		return $this->nodes->getDefaultFields( $node);
	}
	
}