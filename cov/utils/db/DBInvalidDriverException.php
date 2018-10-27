<?php namespace cov\utils\db;

use \cov\core\exceptions\CovException as CovException;
use \Exception as Exception;

/**
 * 
 * @author Ukhando
 *
 */
class DBInvalidDriverException extends CovException {
	
	/**
	 * 
	 * @param Exception $previous
	 */
	public function __construct( Exception $previous = null){
		parent::__construct("invalid driver", 62501, $previous);
	}
	
}
