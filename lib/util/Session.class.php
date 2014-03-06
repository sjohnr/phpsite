<?php

/**
 * Session class.
 * This class statically wraps $_SESSION.
 *
 * @author Stephen Riesenberg
 */
class Session extends Map {
	private static $instance = null;
	
	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		session_name(Config::getInstance()->get('session_name', 'default'));
		session_start();
		
		parent::__construct($_SESSION, Map::ROOT_MODE);
	}
	
	/**
	 * Singleton.
	 *
	 * @return object
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new Session();
		}
		
		return self::$instance;
	}
}
