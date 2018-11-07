<?php namespace cov\utils\api\rest;

use cov\utils\db\DB;
use cov\utils\api\rest\exceptions\FunctionNotSupported;

/**
 * 
 * @author Ukhando
 *
 */
abstract class NodeController {
	
	/**
	 * 
	 * @var Nodes
	 */
	private $nodes;
	
	/**
	 * 
	 * @param Nodes $nodes
	 */
	public function setNodes( Nodes $nodes){
		$this->nodes = $nodes;
	}
	
	/**
	 * 
	 * @param string $node
	 * @return NodeController
	 */
	public function getController( string $node){
		return $this->nodes->getController( $node);
	}
	
	/**
	 * 
	 * @param string $node
	 * @return Field
	 */
	public function getDefaultFields( string $node){
		return $this->nodes->getDefaultFields( $node);
	}
	
	/**
	 *
	 * @param string $parent
	 * @param string $parentid
	 * @param Field $fields
	 * @param DB $database
	 */
	public function getFromParent( string $parent, string $parentid, Field $fields, DB $database){
		throw new FunctionNotSupported( "getFromParent");
	}
	
	/**
	 * 
	 * @param string $id
	 * @param Field $fields
	 * @param DB $database
	 * @param Nodes $nodes
	 */
	public function get( string $id, Field $fields, DB $database){
		throw new FunctionNotSupported( "get");
	}
	
	
	/**
	 * 
	 * @param Field $fields
	 * @param DB $database
	 */
	public function getAll( Field $fields, DB $database){
		throw new FunctionNotSupported( "getAll");
	}
	
	/**
	 * 
	 * @param array $data
	 * @param DB $database
	 * @param Nodes $nodes
	 * @return bool
	 */
	public function post( array $data, DB $database) : bool{
		throw new FunctionNotSupported( "post");
	}
	
}