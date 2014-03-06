<?php

/**
 * Map class.
 * Represents a namespaced map implementation, using php arrays.
 *
 * @author Stephen Riesenberg
 */
class Map {
	/**
	 * Default namespace.
	 */
	const DEFAULT_NS = 'default';
	
	/**
	 * Instantiation modes.
	 */
	const DEFAULT_MODE = 0;
	const ROOT_MODE    = 1;
	
	/**
	 * Internal array instance for the map.
	 */
	private $map = array();
	
	/**
	 * Map constructor.
	 *
	 * @param array init
	 * @param int mode
	 */
	public function __construct(&$init = array(), $mode = Map::DEFAULT_MODE) {
		switch ($mode) {
			case Map::DEFAULT_MODE:
				$this->map[Map::DEFAULT_NS] =& $init;
			break;
			case Map::ROOT_MODE:
				$this->map =& $init;
			break;
		}
	}
	
	/**
	 * Set a value in the map.
	 *
	 * @param string name
	 * @param string|array|object value
	 * @param string ns
	 */
	public function set($name, $value, $ns = Map::DEFAULT_NS) {
		if (!isset($this->map[$ns])) {
			$this->map[$ns] = array();
		}
		
		$this->map[$ns][$name] = $value;
	}
	
	/**
	 * Get a value in the map.
	 *
	 * @param string name
	 * @param string|array|object default
	 * @param string ns
	 */
	public function get($name, $default = null, $ns = Map::DEFAULT_NS) {
		return (!isset($this->map[$ns]) || !isset($this->map[$ns][$name])) ? $default : $this->map[$ns][$name];
	}
	
	/**
	 * Determine if a value is in the map.
	 *
	 * @param string name
	 * @param string ns
	 * @return boolean
	 */
	public function has($name, $ns = Map::DEFAULT_NS) {
		return (isset($this->map[$ns]) && isset($this->map[$ns][$name]));
	}
	
	/**
	 * Remove a values from the map.
	 *
	 * @param string name
	 * @param string ns
	 */
	public function remove($name, $ns = Map::DEFAULT_NS) {
		if (isset($this->map[$ns]) && isset($this->map[$ns][$name])) {
			unset($this->map[$ns][$name]);
		}
	}
	
	/**
	 * Get all values in a namespace.
	 *
	 * @param string ns
	 * @return array
	 */
	public function getAll($ns = Map::DEFAULT_NS) {
		return (!isset($this->map[$ns])) ? array() : $this->map[$ns];
	}
	
	/**
	 * Add all values into a namespace.
	 *
	 * @param array arr
	 * @param string ns
	 */
	public function add($arr, $ns = Map::DEFAULT_NS) {
		if (!isset($this->map[$ns])) {
			$this->map[$ns] = array();
		}
		
		foreach ($arr as $key => $value) {
			$this->map[$ns][$key] = $value;
		}
	}
}
