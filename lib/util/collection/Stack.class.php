<?php

/**
 * Stack class.
 * A classic last-in, first-out (LIFO) stack implementation, using php arrays.
 *
 * @author Stephen Riesenberg
 */
class Stack {
	private $stack = array();
	
	/**
	 * Stack constructor.
	 *
	 */
	public function __construct() {
		
	}
	
	/**
	 * Push an entry onto the stack.
	 *
	 * @param string|array|object entry
	 *
	 * @see array_push
	 */
	public function push($entry) {
		array_push($this->stack, $entry);
	}
	
	/**
	 * Pop an entry off of the stack, lowering the stack's size by 1.
	 *
	 * @return string|array|object
	 *
	 * @see array_pop
	 */
	public function pop() {
		return array_pop($this->stack);
	}
	
	/**
	 * Peek at the last entry on the stack.
	 *
	 * @return string|array|object
	 */
	public function peek() {
		return $this->stack[count($this->stack)-1];
	}
	
	/**
	 * Query the size of the stack.
	 *
	 * @return integer
	 */
	public function size() {
		return count($this->stack) ? count($this->stack) : 0;
	}
	
	/**
	 * Determine if the stack is empty.
	 *
	 * @return boolean
	 */
	public function isEmpty() {
		return count($this->stack) == 0;
	}
}

?>
