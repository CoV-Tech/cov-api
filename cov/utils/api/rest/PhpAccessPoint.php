<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger;

/**
 * 
 * @author Ukhando
 *
 */
class PhpAccessPoint {
	
	/**
	 * @var RoutesConfig $routes
	 * @var Logger $logger
	 */
	private $routes, $logger;
	
	/**
	 * 
	 * @param RoutesConfig $routes
	 */
	public function __construct( Logger $logger, RoutesConfig $routes){
		$routes->addRoute("GET", "dev", new DevEndpoint( $routes));
		$this->routes = $routes;
		$this->logger = $logger;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getBaseUrl(){
		return $this->routes->getBaseUrl();
	}
	
	/**
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function main( Request $request){
		$route = null;
		try{
			$route = $this->routes->getRoute($this->logger, $request->getUrl(), $request->getMethod());
		}catch( MultipleRoutesException $e){
			$this->logger->write( "multiple routes available for : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("multiple routes");
		}
		if ($route === null){
			$this->logger->write( "route ".$request->getMethod()." ".$request->getUrl()."  not supported", "ERROR");
			return Response::createFromStatus("route not supported");
		}
		if ($route->getEndpoint() === null){
			$this->logger->write( "unknown route error, no endPoint found for : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("unknown route error, no endPoint found");
		}
		$request->parsedUrl( $route->parse( $this->logger, $request->getUrl()));
		$response = $route->getEndpoint()->main( $this->logger, $request);
		if ($response == null || $response->getStatus() == null){
			$this->logger->write( "unknown route error, no response given for route : ".$request->getMethod()." ".$request->getUrl(), "ERROR");
			return Response::createFromStatus("unknown route error, no response given");
		}
		$this->logger->write( "request succesfull : ".$request->getMethod()." ".$request->getUrl(), "INFO");
		return $response;
	}
}