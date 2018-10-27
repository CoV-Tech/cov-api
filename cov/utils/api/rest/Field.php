<?php namespace cov\utils\api\rest;

use \TypeError as TypeError;
use \JsonSerializable as JsonSerializable;

/**
 * 
 * @author Ukhando
 *
 */
class Field implements JsonSerializable{
	
	/**
	 * 
	 * @var string $name
	 * @var Field[] $subFields
	 */
	private $name, $subFields;
	
	/**
	 * 
	 * @param string $name
	 * @param array $subFields
	 * @throws TypeError
	 */
	public function __construct( string $name, array $subFields = array()){
		foreach ($subFields as $subField){
			if (get_class($subField) != get_class()){
				throw new TypeError( "subFields must be an array of Field's, ".get_class($subField)." given");
			}
		}
		if (!preg_match("/^[a-zA-Z0-9]*$/", $name)){
			throw new TypeError( "name must be in the form ^[a-zA-Z0-9]*$");
		}
		$this->name = $name;
		$this->subFields = $subFields;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getName() : string{
		return $this->name;
	}
	
	/**
	 * 
	 * @return Field[]
	 */
	public function getSubFields() : array{
		return $this->subFields;
	}
	
	public static function parseField( string $get) : Field{
		$fields = Self::parseFromString($get);
		if (count($fields) == 1){
			return $fields[0];
		}else{
			return new Field( "main", $fields);
		}
	}
	
	/**
	 * 
	 * @param string $string
	 * @return Field[]
	 */
	public static function parseFromString( string $string) : array{
		$offset = 0;
		$fields = array();
		while ($offset < strlen($string)){
			$firstC = stripos($string, ",", $offset) == false ? strlen($string) : stripos($string, ",", $offset);
			$firstB = stripos($string, "{", $offset) == false ? strlen($string) : stripos($string, "{", $offset);
			$firstE = stripos($string, "}", $offset) == false ? strlen($string) : stripos($string, "}", $offset);
			$name = substr($string, $offset, min( $firstC, $firstB, $firstE)-$offset);
			$char = substr($string, $offset+strlen($name),1);
			$subFields = array();
			$offset += strlen($name)+1;
			if ($char == "{"){
				$subFields = Self::parseFromString(substr($string, $offset));
				$arr = str_split(substr($string, $offset), 1);
				$i = 0;
				$j = 0;
				while ($j >= 0){
					if ($arr[$i] == "{"){
						$j++;
					}elseif($arr[$i] == "}"){
						$j--;
					}
					$i++;
				}
				$offset += $i+1;
			}
			try{
				$fields[] = new Field($name, $subFields);
			}catch( TypeError $e){
				throw new ParseException();
			}
			if ($char == "}"){
				return $fields;
			}
		}
		return $fields;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function __toString() : string{
		$str = $this->name;
		if (count($this->subFields) > 0){
			$str .= "{";
			foreach( $this->subFields as $subField){
				$str .= $subField->__toString().",";
			}
			$str = rtrim($str, ",");
			$str .= "}";
		}
		return $str;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function toArray() : array{
		return array(
				"name" => $this->name,
				"subFields" => $this->subFields
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize(){
		return $this->toArray();
	}
	
	
}