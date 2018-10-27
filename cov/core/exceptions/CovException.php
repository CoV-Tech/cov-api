<?php namespace cov\core\exceptions;

use \Exception as Exception;

/**
 * 
 * @author Ukhando
 *
 */
class CovException extends Exception{
	
	/**
	 * 
	 * @param string $message
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct( string $message = "", int $code = 0, Exception $previous = null){
	    parent::__construct( $message, $code, $previous);
	}
	
}
