<?php namespace cov\utils\api\rest;

use cov\core\exceptions\CovException;
use \Exception as Exception;

/**
 * 
 * @author Ukhando
 *
 */
class ParseException extends CovException{
	
	/**
	 *
	 * @param Exception $previous
	 */
	public function __construct( Exception $previous = null){
		parent::__construct("parsing failed", 62140, $previous);
	}
	
}
