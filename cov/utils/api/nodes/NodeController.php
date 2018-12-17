<?php namespace cov\utils\api\nodes;

use cov\utils\db\DB;
use cov\utils\api\rest\exceptions\FunctionNotSupported;
use cov\utils\api\rest\Field;

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
     * @throws FunctionNotSupported
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
     * @throws FunctionNotSupported
     */
	public function get( string $id, Field $fields, DB $database){
		throw new FunctionNotSupported( "get");
	}


    /**
     *
     * @param Field $fields
     * @param DB $database
     * @throws FunctionNotSupported
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
     * @throws FunctionNotSupported
     */
	public function post( array $data, DB $database) : bool{
		throw new FunctionNotSupported( "post");
	}

	public function update( array $data, DB $database, string $id) : bool{
        throw new FunctionNotSupported( "update");
    }
	
}