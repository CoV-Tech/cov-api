<?php namespace cov\utils\api\rest\nodes;


class NodeField{
	
	/**
	 *
	 * @var string $type
	 * @var bool $array
	 */
	private $type, $array;
	
	/**
	 * 
	 * @param string $type
	 * @param bool $isArray
	 */
	public function __construct( string $type, bool $isArray = false){
		$this->type = $type;
		$this->array = $array;
	}
	
	/**
	 * 
	 * @return bool
	 */
	public function isArray() : bool{
		return $this->array;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getType() : string{
		return $this->type;
	}
	
}