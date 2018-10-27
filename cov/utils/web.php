<?php namespace cov\utils;

/**
 * 
 * @author Ukhando
 *
 */
class Web {
	
	/**
	 * @param string $bytes
	 * @return NULL|string
	 */
	public static function base64_web_encode( string $bytes){
		if ($bytes == null){
			return null;
		}
		$ret = base64_encode( $bytes);
		$ret = str_ireplace( "/", "_", $ret);
		$ret = str_ireplace( "+", "-", $ret);
		$ret = str_ireplace( "=", ".", $ret);
		$ret = rtrim($ret, ".");
		return $ret;
	}
	
	/**
	 * 
	 * @param string $base64
	 * @return NULL|string
	 */
	public static function base64_web_decode( string $base64){
		if ($base64 == null){
			return null;
		}
		$ret = str_ireplace( ".", "=", $base64);
		$ret = str_ireplace( "-", "+", $ret);
		$ret = str_ireplace( "_", "/", $ret);
		while (strlen($ret) % 4 != 0){
			$ret .= "=";
		}
		$ret = base64_decode($ret);
		return $ret;
	}
	
	/**
	 * 
	 * @param string $key
	 * @return string|NULL
	 */
	public static function getHeader( string $key){
		$headers = getallheaders();
		foreach ($headers as $h_key => $h_value){
			if ($key == $h_key){
				return $h_value;
			}
		}
		return null;
	}
	
	public static function arrayToString( $array = array()){
		$strings = array();
		foreach ($array as $item){
			$strings[] = $item->__toString();
		}
		return $strings;
	}
}