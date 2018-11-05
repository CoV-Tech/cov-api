<?php namespace cov\utils\api\rest;

use cov\utils\db\DB;

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
	 * @param string $parent
	 * @param string $parentid
	 * @param Field $fields
	 * @param DB $database
	 */
	public abstract function getFromParent( string $parent, string $parentid, Field $fields, DB $database);
	
	/**
	 * 
	 * @param string $id
	 * @param Field $fields
	 * @param DB $database
	 * @param Nodes $nodes
	 */
	public abstract function get( string $id, Field $fields, DB $database);
	
	/**
	 * 
	 * @param unknown $data
	 * @param DB $database
	 * @param Nodes $nodes
	 * @return bool
	 */
	public abstract function post( $data, DB $database) : bool;
	
}