<?php namespace cov\utils\api\rest\nodes;

/**
 *
 * @author Ukhando
 *
 */
class Node {
	
	/**
	 *
	 * @var NodeField[]
	 */
	private $fields;
	
	/**
	 *
	 */
	public function __construct(){
		$this->fields = array();
	}
	
	/**
	 *
	 * @param string $name
	 * @param string $type
	 */
	public function addField( string $name, string $type){
		$typeName = $type;
		$isArray = false;
		if (substr($type, -2) === "[]"){
			$typeName = substr($type, 0, strlen($type)-2);
			$isArray = true;
		}
		$this->fields[$name]= new NodeField( $typeName, $isArray);
	}
	
	/**
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isField( string $name) : bool{
		return isset($this->fields[$name]);
	}
	
	/**
	 *
	 * @param string $name
	 * @return NULL|NodeField
	 */
	public function getField( string $name){
		return $this->isField($name) ? $this->fields[$name] : null;
	}
	
	/**
	 *
	 * @return NodeField[]
	 */
	public function getFields(){
		return $this->fields;
	}
	
	/**
	 *
	 * @param string $name
	 * @return NULL|string
	 */
	public function getFieldType( string $name){
		$field = $this->getField($name);
		if ($field === null){
			return null;
		}
		return $field->getType();
	}
	
	/**
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isFieldArray( string $name) : bool{
		$field = $this->getField($name);
		if ($field === null){
			return false;
		}
		return $field->isArray();
	}
	
}