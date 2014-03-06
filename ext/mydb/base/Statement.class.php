<?php

/**
 * Statement interface.
 *
 * @author Stephen Riesenberg
 */
interface Statement {
	/**
	 * Execute this prepared statement.
	 *
	 * @param array params
	 * @param integer mode
	 * @return integer|object ResultSet
	 */
	function execute($params = array(), $mode = ResultSet::FETCHMODE_NUM);
	
	/**
	 * Execute this prepared statement as a SELECT statement.
	 *
	 * @param array params
	 * @param integer mode
	 * @return object ResultSet
	 */
	function query($params = array(), $mode = ResultSet::FETCHMODE_NUM);
	
	/**
	 * Execute this prepared statement as an INSERT, UPDATE, or DELETE statement.
	 *
	 * @param array params
	 * @param integer mode
	 * @return integer
	 */
	function update($params = array(), $mode = ResultSet::FETCHMODE_NUM);
	
	/**
	 * Parse the sql for params, and prepare this sql statement.
	 *
	 * @param string sql
	 */
	function prepare($sql);
	
	/**
	 * Bind parameters to the sql statement.
	 *
	 * @param array params
	 * @return string
	 */
	function bind($params = array());
}

?>
