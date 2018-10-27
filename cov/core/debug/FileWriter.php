<?php namespace cov\core\debug;

use cov\core\exceptions\CovException;

/**
 * 
 * @author Ukhando
 *
 */
class FileWriter extends Logger {
	
	private $fileLocation;
	
	/**
	 * 
	 * @param string $fileLocation
	 */
	public function __construct( string $fileLocation){
		$this->fileLocation = $fileLocation;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	private function checkLogFile() : bool{
		if (file_exists($this->fileLocation)){
			return true;
		}
		return true;
		
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Logger::log()
	 */
	public function log( string $data) : void{
		$this->writeToFile($data);
	}
	
	/**
	 * 
	 * @param string $data
	 * @throws CovException
	 */
	public function writeToFile( string $data) : void{
		if (!$this->checkLogFile()){
			throw new CovException( "File not available", 5214);
		}
		$f = fopen($this->fileLocation, "a");
		fwrite($f, $data."\n");
		fclose($f);
	}
	
}
