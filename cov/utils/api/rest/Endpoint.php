<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger as Logger;

/**
 * 
 * @author Ukhando
 *
 */
interface Endpoint {
	
	/**
	 * 
	 * @param Logger $logger
	 * @param Request $request
	 * @return Response
	 */
	public function main( Logger $logger, Request $request);
	
}