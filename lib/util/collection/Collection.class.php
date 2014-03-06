<?php

/**
 * Collection interface.
 * Represents a namespaced collection of values.
 *
 * @author Stephen Riesenberg
 */
interface Collection {
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
	 * Add an element to the collection.
	 *
	 * @param string|array|object value
	 * @param string ns
	 * @return integer
	 */
	public function add($value, $ns = Collection::DEFAULT_NS);
	
	/**
	 * Get an element from the collection, at the given index.
	 *
	 * @param integer idx
	 * @param string|array|object default
	 * @param string ns
	 * @return string|array|object
	 */
	public function get($idx = 0, $default = null, $ns = Collection::DEFAULT_NS);
	
	/**
	 * Determine if a value is in the collection.
	 *
	 * @param string|array|object value
	 * @param string ns
	 * @return boolean
	 */
	public function has($value, $ns = Collection::DEFAULT_NS);
	
	/**
	 * Remove an element from the collection, at the given index.
	 *
	 * @param integer idx
	 * @param string ns
	 * @return string|array|object
	 */
	public function remove($idx = 0, $ns = Collection::DEFAULT_NS);
}

?>
