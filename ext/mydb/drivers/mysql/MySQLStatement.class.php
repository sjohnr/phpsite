<?php

/**
 * MySQLStatement class.
 *
 * @author Stephen Riesenberg
 */
class MySQLStatement implements Statement {
	/**
	 * MySQLConnection object.
	 */
	private $conn;
	
	/**
	 * Cached SQL statement, parsed for parameters.
	 */
	private $sql;
	
	/**
	 * Parameter positions array.
	 */
	private $positions;
	
	/**
	 * MySQLStatement constructor.
	 *
	 * @param object conn MySQLConnection
	 * @param string sql
	 */
	public function __construct($conn, $sql) {
		$this->conn = $conn;
		
		$this->prepare($sql);
	}
	
	/**
	 * Execute this prepared statement.
	 *
	 * @param array params
	 * @param integer mode
	 * @return integer|object ResultSet
	 */
	public function execute($params = array(), $mode = ResultSet::FETCHMODE_ASSOC) {
		if (stripos($this->sql, "SELECT") === true) {
			return $this->query($params, $mode);
		} else {
			return $this->update($params, $mode);
		}
	}
	
	/**
	 * Execute this prepared statement as a SELECT statement.
	 *
	 * @param array params
	 * @param integer mode
	 * @return object ResultSet
	 */
	public function query($params = array(), $mode = ResultSet::FETCHMODE_ASSOC) {
		$sql = $this->bind($params);
		
		return $this->conn->query($sql, $mode);
	}
	
	/**
	 * Execute this prepared statement as an INSERT, UPDATE, or DELETE statement.
	 *
	 * @param array params
	 * @param integer mode
	 * @return integer
	 */
	public function update($params = array(), $mode = ResultSet::FETCHMODE_ASSOC) {
		$sql = $this->bind($params);
		
		return $this->conn->update($sql, $mode);
	}
	
	/**
	 * Parse the sql for params, and prepare this sql statement.
	 *
	 * @param string sql
	 */
	public function prepare($sql) {
		$positions = array();
		$idx       = -1;
		
		while (($idx = strpos($sql, ":", $idx+1)) !== false) {
			// search for non-alpha character
			for ($idx2 = $idx+1; preg_match("/^[_a-zA-Z0-9]$/", substr($sql, $idx2, 1)) && $idx2 < strlen($sql); $idx2++);
			
			// parse param
			$param = substr($sql, $idx+1, $idx2-$idx-1);
			$positions[$idx] = $param;
			
			// rebuild SQL statement with ? in place of :param
			$sql = substr($sql, 0, $idx)."?".substr($sql, $idx+strlen($param)+1);
		}
		
		// cache results
		$this->sql       = $sql;
		$this->positions = $positions;
	}
	
	/**
	 * Bind parameters to the sql statement.
	 *
	 * @param array params
	 * @return string
	 */
	public function bind($params = array()) {
		$values = array();
		$sql = "";
		$prev = 0;
		foreach ($this->positions as $idx => $param) {
			// build escaped and quoted value
			$value = "'{$this->conn->escape($params[$param])}'"; // TODO: refactor quotes
			
			// incrementally build SQL statement with value
			$sql .= substr($this->sql, $prev, $idx-$prev).$value;
			$prev = $idx+1;
		}
		
		// add final piece of SQL
		$sql .= substr($this->sql, $prev);
		
		return $sql;
	}
}

?>
