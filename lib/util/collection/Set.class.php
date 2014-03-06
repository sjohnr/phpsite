<?php

/**
 * Set class.
 * Represents a namespaced set of values, containing no duplicates in each namespace.
 *
 * @author Stephen Riesenberg
 */
class Set extends List {
	/**
	 * Set constructor.
	 *
	 * @param array init
	 * @param int mode
	 */
	public function __construct($init = array(), $mode = Collection::DEFAULT_MODE) {
		parent::__construct($init, $mode);
	}
	
	/**
	 * Add an element to the set, if it is not already present.
	 *
	 * @param string|array|object value
	 * @param string ns
	 * @return integer
	 */
	public function add($value, $ns = Collection::DEFAULT_NS) {
		if (!$this->has($value, $ns)) {
			return parent::add($value, $ns);
		}
		
		return false;
	}
}

?>
