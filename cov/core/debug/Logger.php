<?php namespace cov\core\debug;

/**
 * 
 * @author Ukhando
 *
 */
abstract class Logger {
	
	/**
	 * 
	 * @return string
	 */
	private function get_calling_class() : string{
		
		//get the trace
		$trace = debug_backtrace();
		
		// Get the class that is asking for who awoke it
		$class = $trace[1]['class'];
		
		// +1 to i cos we have to account for calling this function
		for ( $i=1; $i<count( $trace ); $i++ ) {
			if ( isset( $trace[$i] ) ) // is it set?
				if ( $class != $trace[$i]['class'] ) // is it a different class
				return $trace[$i]['class'];
		}
	}
	
	/**
	 * 
	 * @var LogParameters $params
	 */
	private $params;
	
	/**
	 * 
	 * @param LogParameters $params
	 */
	public function __construct( LogParameters $params){
		$this->params = $params;
	}
	
	/**
	 * 
	 * @param string $data
	 * @param string $type
	 * @param string $object
	 */
	public function write( string $data, string $type = "DEBUG", string $object = null) : void{
		if ($object == null){
			$object = $this->get_calling_class();
		}
		$this->log("[".gmdate('Y/m/d H:i:s')."] [".$type."] [".$object."] [".$data."]");
	}
	
	/**
	 * 
	 * @param string $data
	 */
	public abstract function log( string $data) : void;
	
}
