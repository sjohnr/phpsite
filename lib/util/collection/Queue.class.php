<?php

/**
 * Queue class.
 * A classic first-in, first-out (FIFO) queue implementation, using php arrays.
 *
 * @author Stephen Riesenberg
 */
class Queue {
	private $queue = array();
	
	/**
	 * Queue constructor.
	 *
	 */
	public function __construct() {
		
	}
	
	/**
	 * Push an entry onto the queue.
	 *
	 * @param string|array|object entry
	 *
	 * @see array_push
	 */
	public function push($entry) {
		array_push($this->queue, $entry);
	}
	
	/**
	 * Pop an entry off of the queue, lowering the queue's size by 1.
	 *
	 * @return string|array|object
	 *
	 * @see array_shift
	 */
	public function pop() {
		return array_shift($this->queue);
	}
	
	/**
	 * Peek at the first entry in the queue.
	 *
	 * @return string|array|object
	 */
	public function peek() {
		return $this->queue[0];
	}
	
	/**
	 * Query the size of the queue.
	 *
	 * @return integer
	 */
	public function size() {
		return count($this->queue) ? count($this->queue) : 0;
	}
	
	/**
	 * Determine if the queue is empty.
	 *
	 * @return boolean
	 */
	public function isEmpty() {
		return count($this->queue) == 0;
	}
}

?>
