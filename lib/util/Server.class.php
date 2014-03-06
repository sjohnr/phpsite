<?php

/**
 * Session class.
 * This class statically wraps $_SERVER.
 *
 * @author Stephen Riesenberg
 */
class Server {
	/**
	 * Retrieve the request uri without the script path.
	 *
	 * <b>Example:</b>
	 * http://www.mydomain.com/some/path/index.php/key/value?my=var
	 * => /key/value?my=var
	 */
	public static function getCurrentUri() {
		$SCRIPT_NAME = Server::getScriptName();
		$REQUEST_URI = Server::getRequestUri();
		if (strpos($REQUEST_URI, $SCRIPT_NAME) !== false) {
			$uri = str_replace($SCRIPT_NAME, '', $REQUEST_URI);
		} else {
			$uri = $REQUEST_URI;
		}
		
		return ($uri == '') ? '/' : $uri;
	}
	
	/**
	 * Retrieve the script path.
	 *
	 * The script path is the part after the domain, up until and including the script filename.
	 *
	 * <b>Example:</b>
	 * http://www.mydomain.com/some/path/index.php?my=var
	 * => /some/path/index.php
	 */
	public static function getScriptPath() {
		return str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
	}
	
	/**
	 * Retrieve the script name.
	 *
	 * <b>Example:</b>
	 * http://www.mydomain.com/some/path/index.php?my=var
	 * => /index.php
	 */
	public static function getScriptName() {
		$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
		$SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME'];
		$SCRIPT_PATH = str_replace($DOCUMENT_ROOT, '', $SCRIPT_FILENAME);
		
		return substr($SCRIPT_PATH, strrpos($SCRIPT_PATH, '/'));
	}
	
	/**
	 * Retrieve the request uri.
	 *
	 */
	public static function getRequestUri() {
		return $_SERVER['REQUEST_URI'];
	}
}

?>
