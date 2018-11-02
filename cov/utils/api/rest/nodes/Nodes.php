<?php namespace cov\utils\api\rest\nodes;


/**
 * 
 * @author Ukhando
 *
 */
class Nodes {
	
	/**
	 * 
	 * @var unknown $types
	 * @var Node[] $nodes
	 */
	private $types, $nodes;
	
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
	 */
	public function createNode( string $name){
		if (!Self::isName( $name)){
			return false;
		}
		$this->nodes[$name] = new Node();
		return true;
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
		if (!$this->typeExists($fieldType)){
			return false;
		}
		$this->nodes[$nodeName]->addField( $fieldName, $fieldType);
		return true;
	}
	
	public function getFieldsFromNode( string $nodeName){
		return $this->nodes[$nodeName]->getFields();
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
		if (Self::isDefaultType($type)){
			return true;
		}
		return isset($this->types[$type]);
	}
	
	
	
	/**
	 * 
	 */
	private static const DEFAULT_TYPES = array( "int", "string", "bool");
	
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
		return preg_match( "/^[a-zA-Z]+[a-zA-Z0-9_-]+[a-zA-Z0-9]+$/", $name) === 1;
	}
	
}



