<?php

/**
 * Connection interface.
 *
 * @author Stephen Riesenberg
 */
interface Connection {
	/**
	 * Connect to this database.
	 *
	 * @param array dsn
	 * @param array flags
	 */
	function connect($dsn, $flags = false);
	
	/**
	 * Close the connection to this database.
	 *
	 */
	function close();
	
	/**
	 * Execute a SQL statement.
	 *
	 * @param string sql
	 * @param integer mode
	 * @return object ResultSet
	 */
	function execute($sql, $mode = ResultSet::FETCHMODE_NUM);
	
	/**
	 * Execute a SQL query.
	 *
	 * @param string sql
	 * @param integer mode
	 * @return object ResultSet
	 */
	function query($sql, $mode = ResultSet::FETCHMODE_NUM);
	
	/**
	 * Execute a SQL update.
	 *
	 * @param string sql
	 * @param integer mode
	 * @return integer
	 */
	function update($sql, $mode = ResultSet::FETCHMODE_NUM);
	
	/**
	 * Create an empty prepared statement.
	 *
	 */
	function create();
	
	/**
	 * Prepare a SQL statement to be executed multiple times.
	 *
	 * @param string sql
	 * @return object Statement
	 */
	function prepare($sql);
	
	/**
	 * BEGIN a transaction.
	 *
	 */
	function begin();
	
	/**
	 * COMMIT a transaction.
	 *
	 */
	function commit();
	
	/**
	 * ROLLBACK a transaction.
	 *
	 */
	function rollback();
	
	/**
	 * Apply a limit, if supported.
	 *
	 * @param string sql
	 * @param integer limit
	 * @param integer offset
	 * @return string
	 */
	function limit($sql, $limit, $offset = 0);
	
	/**
	 * Return the last generated id by this database.
	 *
	 */
	function getGeneratedId();
	
	/**
	 * Return the number of rows affected by the last query.
	 *
	 */
	function getAffectedRows();
	
	/**
	 * Escape a string using driver specific escape method.
	 *
	 * @param string str
	 * @return string
	 */
	function escape($str);
}

?>
