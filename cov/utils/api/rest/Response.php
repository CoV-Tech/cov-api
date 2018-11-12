<?php namespace cov\utils\api\rest;

use \JsonSerializable as JsonSerializable;

/**
 * 
 * @author Ukhando
 *
 */
class Response {
	
	/**
	 * 
	 * @var JsonSerializable $data
	 * @var Status $status
	 */
	private $data, $status;
	
	/**
	 * 
	 * @param Status $status
	 * @param JsonSerializable $data
	 */
	public function __construct( Status $status, $data = null){
		$this->data = $data;
		$this->status = $status;
	}
	
	/**
	 * 
	 * @return JsonSerializable
	 */
	public function getData(){
		return $this->data;
	}
	
	/**
	 * 
	 * @return Status
	 */
	public function getStatus(){
		return $this->status;
	}

    /**
     *
     * @param string $message
     * @param null $data
     * @return Response
     */
	public static function createFromStatus( string $message, $data = null){
		$status = Status::getStatus($message);
		return new Response($status, $data);
	}
	
}