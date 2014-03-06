<?php

/**
 * Filter class.
 *
 * @author Stephen Riesenberg
 */
abstract class Filter {
	public abstract function execute($filterChain);
	
	/**
	 * Execute the pre-filter.
	 *
	 */
	public function preExecute() {
		
	}
	
	/**
	 * Execute the post-filter.
	 *
	 */
	public function postExecute() {
		
	}
}

?>
