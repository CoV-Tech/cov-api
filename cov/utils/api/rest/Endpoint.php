<?php namespace cov\utils\api\rest;

use cov\core\debug\Logger as Logger;
use cov\utils\db\DB;

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
	 * @param DB $db
	 * @return Response
	 */
	public function main( Logger $logger, Request $request, DB $db);
	
}