<?php

/**
 * List class.
 * Represents a namespaced list of values.
 *
 * @author Stephen Riesenberg
 */
class List implements Collection {
	/**
	 * Internal array instance for the list.
	 */
	private $list = array();
	
	/**
	 * List constructor.
	 *
	 * @param array init
	 * @param int mode
	 */
	public function __construct($init = array(), $mode = Collection::DEFAULT_MODE) {
		switch ($mode) {
			case Collection::DEFAULT_MODE:
				$this->list[Collection::DEFAULT_NS] = array();
				for ($i = 0; $i < count($init); $i++) {
					$this->add($init[$i], Collection::DEFAULT_NS);
				}
			break;
			case Collection::ROOT_MODE:
				foreach ($init as $key => $value) {
					for ($i = 0; $i < count($init[$key]); $i++) {
						$this->add($init[$key][$i], $key);
					}
				}
			break;
		}
	}
	
	/**
	 * Add an element to the list.
	 *
	 * @param string|array|object value
	 * @param string ns
	 * @return integer
	 */
	public function add($value, $ns = Collection::DEFAULT_NS) {
		if ($this->list[$ns] == null) {
			$this->list[$ns] = array();
		}
		
		$this->list[$ns][] = $value;
		
		return count($this->list[$ns])-1;
	}
	
	/**
	 * Get an element from the list, at the given index.
	 *
	 * @param integer idx
	 * @param string|array|object default
	 * @param string ns
	 * @return string|array|object
	 */
	public function get($idx = 0, $default = null, $ns = Collection::DEFAULT_NS) {
		return ($this->list[$ns] == null || $this->list[$ns][(integer) $idx] == null) ? $default : $this->list[$ns][(integer) $idx];
	}
	
	/**
	 * Determine if a value is in the list.
	 *
	 * @param string|array|object value
	 * @param string ns
	 * @return boolean
	 */
	public function has($value, $ns = Collection::DEFAULT_NS) {
		if ($this->list[$ns] == null) {
			return false;
		}
		
		// perform linear search for item
		foreach ($this->list[$ns] as $tmp) {
			if ($tmp == $value) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Remove an element from the collection, at the given index.
	 * All items at indices idx+1..n are re-indexed.
	 *
	 * @param integer idx
	 * @param string ns
	 * @return string|array|object
	 */
	public function remove($idx = 0, $ns = Collection::DEFAULT_NS) {
		if ($this->list[$ns] == null || $this->list[$ns][$idx] == null) {
			return false;
		}
		
		$size = count($this->list[$ns]);
		$value = $this->list[$ns][$idx];
		for ($i = $idx+1; $i < $size; $i++) {
			$this->list[$ns][$i-1] = $this->list[$ns][$i];
		}
		
		// remove extraneous value
		unset($this->list[$ns][$size-1]);
		
		return $value;
	}
}

?>
