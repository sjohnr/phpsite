<?php

/**
 * ResultSet interface.
 *
 * @author Stephen Riesenberg
 */
interface ResultSet {
	/**
	 * Fetch modes.
	 */
	const FETCHMODE_NUM     = 0;
	const FETCHMODE_ASSOC   = 1;
	
	/**
	 * Seek modes.
	 */
	const SEEKMODE_ABSOLUTE = 0;
	const SEEKMODE_RELATIVE = 1;
	
	/**
	 * Return the first result.
	 *
	 * @return array
	 */
	function first();
	
	/**
	 * Return the next result.
	 *
	 * @return array
	 */
	function next();
	
	/**
	 * Return the last result.
	 *
	 * @return array
	 */
	function last();
	
	/**
	 * Return the previous result.
	 *
	 * @return array
	 */
	function previous();
	
	/**
	 * Return a given result.
	 *
	 * @param integer rownum
	 * @param integer mode
	 * @return array
	 */
	function seek($rownum, $mode = ResultSet::SEEKMODE_ABSOLUTE);
	
	/**
	 * Retrieve the number of rows in this result set.
	 *
	 * @return integer
	 */
	function getNumRows();
	
	/**
	 * Get a column from the current row of the result set.
	 *
	 * @param integer|string column
	 * @return integer|float|double|string|object
	 */
	function get($column);
	/*
	function getArray($column);
	function getBlog($column);
	function getBoolean($column);
	function getClob($column);
	function getDate($column);
	function getFloat($column);
	function getInt($column);
	function getString($column);
	function getTime($column);
	function getTimestamp($column);
	function getIterator();
	*/
}

?>
