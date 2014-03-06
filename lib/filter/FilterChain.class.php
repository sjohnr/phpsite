<?php

/**
 * FilterChain class.
 *
 * @author Stephen Riesenberg
 */
class FilterChain {
	/**
	 * Entire filter chain.
	 */
	private $chain = array();
	
	/**
	 * Index of current filter.
	 */
	private $index = -1;
	
	/**
	 * Adds a filter to the chain.
	 *
	 * @param object filter
	 */
	public function addFilter($filter) {
		$this->chain[] = $filter;
	}
	
	/**
	 * Execute the next filter in this chain.
	 *
	 */
	public function execute() {
		if ($this->index < count($this->chain) - 1) {
			$filter = $this->chain[++$this->index];
			
			$filter->preExecute();
			$filter->execute($this);
			$filter->postExecute();
		}
	}
}

?>
