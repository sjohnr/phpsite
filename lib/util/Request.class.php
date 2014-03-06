<?php

/**
 * Request class.
 * This class statically wraps $_REQUEST.
 *
 * @author Stephen Riesenberg
 */
class Request extends Map {
	private static $instance = null;
	
	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		parent::__construct($_REQUEST, Map::DEFAULT_MODE);
	}
	
	/**
	 * Singleton.
	 *
	 * @return object
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new Request();
		}
		
		return self::$instance;
	}
}

?>
