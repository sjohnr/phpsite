<?php

/**
 * Response class.
 * This class contains response variables set by various system components.
 *
 * This class stores:
 * 1. All HTTP response headers.
 * 2. All source paths for <script type="text/javascript"> tags.
 * 3. All link paths for <link rel="stylesheet" type="text/css"> tags.
 * These items are generated and output automatically by the system.
 *
 * @author Stephen Riesenberg
 */
class Response extends Map {
	private static $instance = null;
	
	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Singleton.
	 *
	 * @return object
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new Response();
		}
		
		return self::$instance;
	}
	
	/**
	 * Set an HTTP response header.
	 *
	 * @param string header
	 * @param string value
	 */
	public function addHttpHeader($header, $value) {
		$this->set($header, $value, 'http_headers');
	}
	
	/**
	 * Get all HTTP response headers.
	 *
	 * @return array
	 */
	public function getHttpHeaders() {
		return $this->getAll('http_headers');
	}
}

?>
