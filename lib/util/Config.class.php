<?php

/**
 * Config class.
 * Contains the configuration values for the request.
 *
 * @author Stephen Riesenberg
 */
class Config extends Map {
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
			self::$instance = new Config();
		}
		
		return self::$instance;
	}
	
	/**
	 * Overridden get method to add functionality for nested configuration.
	 *
	 * @param string name A config name, with subkeys separated by dot (.)
	 * @param string|array|object default The default to return if the key is not present
	 * @param string ns The namespace
	 */
	public function get($name, $default = null, $ns = Map::DEFAULT_NS) {
		$keys = explode('.', $name);
		if (count($keys) == 1) {
			return parent::get($name, $default, $ns);
		} else {
			return $this->getNestedValue($keys, $default, $ns);
		}
	}
	
	private function getNestedValue($keys, $default = null, $ns = Map::DEFAULT_NS) {
		$result = parent::get($keys[0], null, $ns);
		for ($i = 1; $result != null && $i < count($keys); $i++) {
			$key = $keys[$i];
			if (is_array($result)) {
				$result = isset($result[$key]) ? $result[$key] : null;
			} else {
				$result = null;
			}
		}
		
		return ($result == null) ? $default : $result;
	}
	
	/**
	 * Overriden add method, to flatten before adding to the Map.
	 *
	 * @param array ar
	 * @param string ns
	 *
	public function add($ar, $ns = Map::DEFAULT_NS) {
		foreach (array_keys($ar) as $key) {
			$value = $this->get($key, null, $ns);
			if ($value && is_array($ar[$key]) && is_array($value)) {
				$this->deepMerge($ar[$key], $value);
				$this->set($key, $value, $ns);
			} else {
				$this->set($key, $ar[$key], $ns);
			}
		}
	}
	
	/**
	 * Deep merge an array to avoid blowing away nested arrays with keys.
	 *
	 * @param array|string ar1
	 * @param array|string ar2
	 *
	private function deepMerge($ar1, &$ar2) {
		foreach (array_keys($ar1) as $key) {
			if (isset($ar2[$key]) && is_array($ar1[$key]) && is_array($ar2[$key])) {
				$this->deepAdd($ar1[$key], $ar2[$key]);
			} else {
				$ar2[$key] = $ar1[$key];
			}
		}
	}*/
}

?>
