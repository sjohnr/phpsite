<?php

/**
 * MySQLConnection class.
 *
 * @author Stephen Riesenberg
 */
class MySQLConnection implements Connection {
	/**
	 * mysqli connection resource.
	 */
	private $conn;
	
	/**
	 * Connect to MySQL.
	 * <username>:<password>@mysql://<hostspec>:<port>/<database>
	 *
	 * @param array dsn (username, password, database[, hostspec][, port][, socket])
	 * @param array flags ([persistent])
	 *
	 * @see mysqli_connect
	 */
	public function connect($dsn, $flags = false) {
		if (!extension_loaded('mysqli')) {
			throw new Exception('mysqli extension not loaded!');
		}
		
		// connect to database
		$dbhost     = isset($dsn['hostspec']) ? $dsn['hostspec'] : 'localhost';
		$dbusername = isset($dsn['username']) ? $dsn['username'] : '';
		$dbpassword = isset($dsn['password']) ? $dsn['password'] : '';
		$dbname     = isset($dsn['database']) ? $dsn['database'] : '';
		$dbport     = isset($dsn['port'])     ? $dsn['port']     : 3306;
		$dbsocket   = isset($dsn['socket'])   ? $dsn['socket']   : '';
		
		// initiate connection
		$conn = null;
		if ($dbsocket) {
			$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname, $dbport, $dbsocket);
		} else if ($dbport) {
			$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname, $dbport);
		} else {
			$conn = mysqli_connect($dbhost, $dbusername, $dbpassword, $dbname);
		}
		
		// error?
		if (!$conn) {
			switch(mysqli_errno()) {
				case 1049:
					throw new Exception(sprintf('No such database: %s', mysqli_error()));         
				break;
				case 1044:
					throw new Exception(sprintf('Access violation: %s', mysqli_error()));
				break;
				default:
					throw new Exception(sprintf('Could not connect to db (%s): %s (%s)', $db, mysqli_error(), mysqli_errno()));
			}
		}
		
		// cache this connection
		$this->conn = $conn;
	}
	
	/**
	 * Close the connection to MySQL.
	 *
	 * @see mysqli_close
	 */
	public function close() {
		mysqli_close($this->conn);
		$this->conn = null;
	}
	
	/**
	 * Execute a SQL statement.
	 *
	 * @param string sql
	 * @param integer mode
	 * @return integer|object ResultSet
	 */
	public function execute($sql, $mode = ResultSet::FETCHMODE_ASSOC) {
		if (stripos($sql, 'SELECT') === true) {
			return $this->query($sql, $params, $mode);
		} else {
			return $this->update($sql, $params, $mode);
		}
	}
	
	/**
	 * Execute a SQL query.
	 *
	 * @param string sql
	 * @param integer mode
	 * @return object ResultSet
	 *
	 * @see mysqli_query
	 */
	public function query($sql, $mode = ResultSet::FETCHMODE_ASSOC) {
		$result = @mysqli_query($this->conn, $sql);
		if (!$result) {
			throw new Exception(sprintf('Could not execute query: %s :: %s', mysqli_error($this->conn), $sql));
		}
		
		return new MySQLResultSet($this, $result, $mode);
	}
	
	/**
	 * Execute a SQL update.
	 *
	 * @param string sql
	 * @param integer mode
	 * @return integer
	 *
	 * @see mysqli_query
	 * @see mysqli_affected_rows
	 */
	public function update($sql, $mode = ResultSet::FETCHMODE_ASSOC) {
		$result = @mysqli_query($this->conn, $sql);
		if (!$result) {
			throw new Exception(sprintf('Could not execute update: %s :: %s', mysqli_error($this->conn), $sql));
		}
		
		return (int) mysqli_affected_rows($this->conn);
	}
	
	/**
	 * Create an empty prepared statement.
	 *
	 */
	public function create() {
		return new MySQLStatement($this);
	}
	
	/**
	 * Prepare a SQL statement to be executed multiple times.
	 *
	 * @param string sql
	 * @return object Statement
	 */
	public function prepare($sql) {
		return new MySQLStatement($this, $sql);
	}
	
	/**
	 * BEGIN a transaction.
	 *
	 */
	public function begin() {
		$result = @mysqli_query('SET AUTOCOMMIT=0', $this->conn);
		$result = @mysqli_query('BEGIN', $this->conn);
		if (!$result) {
			throw new Exception(sprintf('Could not begin transaction: %s', mysqli_error($this->conn)));
		}
	}
	
	/**
	 * COMMIT a transaction.
	 *
	 */
	public function commit() {
		$result = @mysqli_query('COMMIT', $this->conn);
		$result = @mysqli_query('SET AUTOCOMMIT=1', $this->conn);
		if (!$result) {
			throw new Exception(sprintf('Could not commit transaction: %s', mysqli_error($this->conn)));
		}
	}
	
	/**
	 * ROLLBACK a transaction.
	 *
	 */
	public function rollback() {
		$result = @mysqli_query('ROLLBACK', $this->conn);
		$result = @mysqli_query('SET AUTOCOMMIT=1', $this->conn);
		if (!$result) {
			throw new Exception(sprintf('Could not rollback transaction: %s', mysqli_error($this->conn)));
		}
	}
	
	/**
	 * Apply a limit, if supported.
	 *
	 * @param string sql
	 * @param integer limit
	 * @param integer offset
	 * @return string
	 */
	public function limit($sql, $limit, $offset = 0) {
		return $sql . " LIMIT {$limit} OFFSET {$offset}";
	}
	
	/**
	 * Return the last generated id by this database.
	 *
	 * @see mysqli_insert_id
	 */
	public function getGeneratedId() {
		return mysqli_insert_id($this->conn);
	}
	
	/**
	 * Return the number of rows affected by the last query.
	 *
	 * @see mysqli_affected_rows
	 */
	 public function getAffectedRows() {
		 return mysqli_affected_rows($this->conn);
	 }
	
	/**
	 * Escape a string using MySQL escape method.
	 *
	 * @param string str
	 * @return string
	 *
	 * @see mysqli_real_escape_string
	 */
	public function escape($str) {
		return mysqli_real_escape_string($this->conn, stripslashes($str));
	}
}

?>
