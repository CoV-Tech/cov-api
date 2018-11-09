<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;
use cov\utils\db\DB;
use cov\utils\Web;

/**
 * 
 * @author Ukhando
 *
 */
class HttpAccessPoint {
	
	/**
	 * 
	 * @var string $version
	 * @var PhpAccessPoint $accessPoint
	 * @var string $auth
	 * @var Logger $logger
	 */
	private $version, $accessPoint, $auth, $logger;
	
	/**
	 * 
	 * @param Logger $logger
	 * @param string $version
	 * @param RoutesConfig $routes
	 * @param DB $db
	 * @param string $auth
	 */
	public function __construct( Logger $logger, string $version, RoutesConfig $routes, DB $db, string $auth = null){
		$this->version     = $version;
		$this->accessPoint = new PhpAccessPoint( $logger, $routes, $db);
		$this->auth        = $auth;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBaseUrl() : string{
		return $this->accessPoint->getBaseUrl();
	}
	
	/**
	 * 
	 * @param string $string
	 * @return Field
	 */
	private function parseFields( string $string) : Field{
		$field = null;
		if (strlen($string) < 1){
			$field = new Field( "main");
		}else{
			$field = new Field( "main", Field::parseFromString( $string));
		}
		return $field;
	}
	
	/**
	 * 
	 */
	public function treatRequest() : void{
		$begin = microtime(true);
		$url = explode( "?", $_SERVER['REQUEST_URI']);
		$url = explode( "/", $url[0]);
		$http = isset($_GET["http"]) ? $_GET["http"] : true;
		$body = "";
		$basePlace = explode("/", $this->getBaseUrl())[0];
		$i = array_search($basePlace, $url);
		$newUrl = array();
		for( $j = $i; $j < count($url); $j++){
			$newUrl[] = $url[$j];
		}
		if ($http === "false" || $http === false){
			$http = false;
		}else{
			$http = true;
		}
		$method = (string) $_SERVER['REQUEST_METHOD'];
		if ($method === "HEAD"){
			$method = "GET";
		}
		$headers = getallheaders();
		$parameters = array();
		foreach( $_GET as $key => $value){
			if (!in_array($key, array("http", "fields"))){
				$parameters[$key] = $value;
			}
		}
		$fields = null;
		$response = null;
		try{
			$fields = $this->parseFields( isset($_GET["fields"]) ? $_GET["fields"] : "");
		}catch ( \Exception $e){
			$response = new Response( new Status(-10, $e->getMessage(), 500), $e->__toString());
		}
		
		if ($response === null){
			$request = new Request( $newUrl, $headers, $body, $parameters, $method, $fields);
			$response = $this->accessPoint->main( $request);
		}
		$responseJson = array(
				"status" => $response->getStatus(),
				"response" => $response->getData(),
				"response_time" => round((microtime(true) - $begin),3),
				"version" => $this->version
		);
		$responseText = json_encode($responseJson, JSON_UNESCAPED_SLASHES);
		if ($responseText === false){
			$response = Response::createFromStatus( "INTERNAL ERROR");
			$responseJson = array(
					"status" => $response->getStatus(),
					"response" => $response->getData(),
					"response_time" => round((microtime(true) - $begin),3),
					"version" => $this->version
			);
			$responseText = json_encode($responseJson, JSON_UNESCAPED_SLASHES);
		}
		$last_updated = isset($responseJson["response"]["last_updated"]) ? $responseJson["response"]["last_updated"] : time();
		
		header( "Content-Type: application/json");
		header( "Access-Control-Allow-Origin: *");
		header( "Access-Control-Allow-Headers: *");
		header( "Content-Language: en");
		header( "Date: ".gmdate('D, d M Y H:i:s T'));
		header( "Last-Modified: ".gmdate( 'D, d M Y H:i:s T', $last_updated));
		if ($this->auth !== null){
			header( 'WWW-Authenticate: Bearer realm="'.$this->auth.'"');
		}
		header( "Tk: !");
		header( "Content-Length: ".strlen($responseText));
		header( $response->getStatus()->getStatusHeader());
		
		if ($http){
			header( $response->getStatus()->getHttpHeader());
		}else{
			header( "HTTP/1.1 200 OK");
		}
		if (Web::getHeader( "If-Modified-Since") !== null){
			$time = \DateTime::createFromFormat( 'D, d M Y H:i:s T', Web::getHeader( "If-Modified-Since"))->getTimestamp();
			if ($time >= $last_updated){
				header( "HTTP/1.1 304 Not Modified");
			}else{
				if ($_SERVER['REQUEST_METHOD'] != "HEAD"){
					echo $responseText;
				}
			}
		}else{
			if ($_SERVER['REQUEST_METHOD'] != "HEAD"){
				echo $responseText;
			}
		}
		

	}
	
	
	
}