<?php

/**
 * Abstract Cache class.
 *
 * @author Stephen Riesenberg
 */
abstract class Cache {
	/**
	 * Parameters.
	 */
	private $params;
	
	/**
	 * Cache constructor.
	 *
	 * @param array params
	 */
	public function __construct($params = array()) {
		$this->params = $params;
	}
	
	/**
	 * Get a parameter.
	 *
	 * @param string param
	 * @param string|array default
	 * @return string|array
	 */
	public function getParameter($param, $default = null) {
		return isset($this->params[$param]) ? $this->params[$param] : $default;
	}
	
	/**
	 * Determine if a parameter exists.
	 *
	 * @param string param
	 * @return boolean
	 */
	public function hasParameter($param) {
		return isset($this->params[$param]);
	}
	
	/**
	 * Set a parameter.
	 *
	 * @param string param
	 * @param string|array value
	 */
	public function setParameter($param, $value) {
		$this->params[$param] = $value;
	}
	
	/**
	 * Get a value from the cache.
	 *
	 * @param string key
	 * @param string default
	 * @return string
	 */
	public abstract function get($key, $default = null);
	
	/**
	 * Determine whether a values is in the cache.
	 *
	 * @param string key
	 * @return boolean
	 */
	public abstract function has($key);
	
	/**
	 * Set a value in the cache.
	 *
	 * @param string key
	 * @param string data
	 * @return boolean
	 */
	public abstract function set($key, $data);
	
	/**
	 * Remove a value in from cache.
	 *
	 * @param string key
	 * @return boolean
	 */
	public abstract function remove($key);
}
