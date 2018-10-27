<?php namespace cov\utils\api\rest;

use \JsonSerializable as JsonSerializable;

/**
 * 
 * @author Ukhando
 *
 */
class Status implements JsonSerializable{
	
	/**
	 * 
	 * @var int $statusCode
	 * @var string $statusMessage
	 * @var int $httpCode
	 * @var string $httpMessage
	 */
	private $statusCode, $statusMessage, $httpCode, $httpMessage;
	
	/**
	 * 
	 * @param int $statusCode
	 * @param string $statusMessage
	 * @param int $httpCode
	 */
	public function __construct( int $statusCode, string $statusMessage, int $httpCode){
		$this->statusCode = $statusCode;
		$this->statusMessage = $statusMessage;
		$this->httpCode = $httpCode;
		$http =  Self::getHttpMessageFromCode($httpCode);
		if ($http !== null){
			$this->httpMessage = $http["message"];
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getHeader(){
		return $this->httpCode." ".$this->httpMessage;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getHttpHeader(){
		return "HTTP/1.1 ".$this->getHeader();
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getStatusHeader(){
		return "Status: ".$this->getHeader();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize(){
		return array(
				"http" => array(
						"code" => $this->httpCode,
						"message" => $this->httpMessage
				),
				"api" => array(
						"code" => $this->statusCode,
						"message" => $this->statusMessage
				)
		);
	}
	
	/**
	 * 
	 * @var array
	 */
	private static $http = array(
			200 => array("code" => 200, "message" => "OK"),
			201 => array("code" => 201, "message" => "Created"),
			400 => array("code" => 400, "message" => "Bad Request"),
			401 => array("code" => 401, "message" => "Unauthorized"),
			403 => array("code" => 403, "message" => "Forbidden"),
			404 => array("code" => 404, "message" => "Not Found"),
			405 => array("code" => 405, "message" => "Method Not Allowed"),
			415 => array("code" => 415, "message" => "Unsupported Media Type"),
			418 => array("code" => 418, "message" => "I'm a teapot"),
			500 => array("code" => 500, "message" => "Internal Server Error"),
			501 => array("code" => 501, "message" => "Not Implemented"),
			503 => array("code" => 503, "message" => "Service Unavailable"),
			507 => array("code" => 507, "message" => "Insufficient Storage")
	);
	
	/**
	 * 
	 * @param int $httpCode
	 * @return JsonSerializable
	 */
	public static function getHttpMessageFromCode( int $httpCode){
		return isset(Self::$http[$httpCode]) ? Self::$http[$httpCode] : array( "code" => $httpCode, "message" => "unknown");
	}
	
	/**
	 * 
	 * @param string $message
	 * @return Status
	 */
	public static function getStatus( string $message){
		switch( $message){
			case "OK":
				return new Status(1, "OK", 200);
			case "route not supported":
				return new Status(2, "route not supported", 501);
			case "multiple routes":
				return new Status(3, "multiple routes, can't chose", 500);
			case "unknown route error, no endPoint found":
				return new Status(4, "unknown route error, no endPoint found", 500);
			case "unknown route error, no response given":
				return new Status(5, "unknown route error, no response given", 500);
			default:
				null;
		}
	}
	
}