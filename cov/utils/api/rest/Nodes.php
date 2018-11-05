<?php namespace cov\utils\api\rest;

use \JsonSerializable as JsonSerializable;

/**
 * 
 * @author Ukhando
 *
 */
class Nodes implements JsonSerializable{
	
	/**
	 * 
	 * @var array $types
	 * @var array $nodes
	 * @var array $nodeDefaults
	 * @var NodeController[] $controllers
	 */
	private $types, $nodes, $nodeDefaults, $controllers;
	
	/**
	 * 
	 */
	public function __construct( ){
		$this->types = array();
		$this->nodes = array();
	}
	
	/**
	 * 
	 * @param string $name
	 * @param NodeController $controller
	 */
	public function createNode( string $name, NodeController $controller){
		if (!Self::isName( $name)){
			return false;
		}
		$this->nodes[$name] = array();
		$controller->setNodes( $this);
		$this->controllers[$name] = $controller;
		return true;
	}
	
	/**
	 * 
	 * @param string $node
	 * @return Field
	 */
	public function getDefaultFields( string $node){
		$fields = array();
		if (isset($this->nodeDefaults[$node])){
			foreach( $this->nodeDefaults[$node] as $field){
				$fields[] = new Field( $field);
			}
		}
		return new Field( "main", $fields);
	}
	
	/**
	 * 
	 * @param string $node
	 * @return NodeController
	 */
	public function getController( string $node){
		return isset( $this->controllers[$node]) ? $this->controllers[$node] : null;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return bool
	 */
	public function nodeExists( string $name) : bool{
		if (!Self::isName($name)){
			return false;
		}
		if (substr($name, -2) == "[]"){
			$name= substr($name, 0, strlen($name)-2);
		}
		return isset($this->nodes[$name]);
	}
	
	/**
	 * 
	 * @param string $nodeName
	 * @param string $fieldName
	 * @param string $fieldType
	 */
	public function addFieldToNode( string $nodeName, string $fieldName, string $fieldType) : bool{
		if (!Self::areNames( $nodeName, $fieldName, $fieldType)){
			return false;
		}
		if (!$this->typeExists($fieldType) && !$this->nodeExists($fieldType)){
			return false;
		}
		if (!$this->nodeExists($nodeName)){
			return false;
		}
		$this->nodes[$nodeName][$fieldName] = $fieldType;
		return true;
	}
	
	public function getFieldsFromNode( string $nodeName){
		return $this->nodes[$nodeName];
	}
	
	/**
	 * 
	 * @param string $type
	 * @return bool
	 */
	public function typeExists( string $type) : bool{
		if (!Self::isName($type)){
			return false;
		}
		if (substr($type, -2) == "[]"){
			$type = substr($type, 0, strlen($type)-2);
		}
		if (Self::isDefaultType($type)){
			return true;
		}
		return isset($this->types[$type]);
	}
	
	/**
	 * 
	 * @param string $type
	 * @return bool
	 */
	public function createType( string $type) : bool{
		if (!Self::isName($type)){
			return false;
		}
		$this->types[$type] = array();
		return true;
	}
	
	/**
	 * 
	 * @param string $typeName
	 * @param string $fieldName
	 * @param string $fieldType
	 * @return bool
	 */
	public function addFieldToType( string $typeName, string $fieldName, string $fieldType) : bool{
		if (!Self::areNames( $typeName, $fieldName, $fieldType)){
			return false;
		}
		if (!$this->typeExists($typeName) && !$this->nodeExists($fieldType) && !$this->typeExists($fieldType)){
			return false;
		}
		$this->types[$typeName][$fieldName] = $fieldType;
		return true;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize(){
		return array(
				"types" => $this->types,
				"nodes" => $this->nodes,
				"default" => $this->nodeDefaults
		);
	}
	
	/**
	 * 
	 * @param string $node
	 * @param string ...$fields
	 * @return bool
	 */
	public function defaultFieldsForNode( string $node, string... $fields) : bool{
		if (!Self::areNames( $node, ...$fields)){
			return false;
		}
		if (!$this->nodeExists($node)){
			return false;
		}
		$this->nodeDefaults[$node] = $fields;
		
		
		return true;
	}
	
	// ////// //
	// STATIC //
	// ////// //
	
	/**
	 * 
	 */
	private const DEFAULT_TYPES = array( "int", "string", "bool");
	
	/**
	 * 
	 * @param string $type
	 * @return bool
	 */
	public static function isDefaultType( string $type) : bool{
		return in_array($type, Self::DEFAULT_TYPES);
	}
	
	/**
	 * 
	 * @param string ...$names
	 * @return bool
	 */
	public static function areNames( string... $names) : bool{
		foreach ($names as $name){
			if (!Self::isName($name)){
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return bool
	 */
	public static function isName( string $name) : bool{
		return preg_match( "/^[a-zA-Z]([a-zA-Z0-9_-]*[a-zA-Z0-9]+)?(\[\])?$/", $name) === 1;
	}
	
}



