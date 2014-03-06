<?php

/**
 * MySQLResultSet class.
 *
 * @author Stephen Riesenberg
 */
class MySQLResultSet implements ResultSet {
	/**
	 * MySQLConnection object.
	 */
	private $conn;
	
	/**
	 * mysqli_result object.
	 */
	private $result;
	
	/**
	 * Current index, or result set row number.
	 */
	private $idx;
	
	/**
	 * Desired fetch-mode.
	 */
	private $mode;
	
	/**
	 * Number of rows in the result set.
	 */
	private $numRows;
	
	/**
	 * Current row from result set.
	 */
	private $cur;
	
	/**
	 * MySQLResultSet constructor.
	 *
	 * @param object MySQLConnection
	 * @param object mysqli_result
	 * @param integer mode
	 */
	public function __construct($conn, $result, $mode = ResultSet::FETCHMODE_NUM) {
		$this->conn    = $conn;
		$this->result  = $result;
		$this->mode    = $mode;
		$this->idx     = 0;
		$this->numRows = @mysqli_num_rows($result);
	}
	
	/**
	 * Return the first result.
	 *
	 * @return array
	 */
	public function first() {
		return $this->seek(0);
	}
	
	/**
	 * Return the next result.
	 *
	 * @return array
	 */
	public function next() {
		return $this->seek($this->idx);
	}
	
	/**
	 * Return the last result.
	 *
	 * @return array
	 */
	public function last() {
		return $this->seek($this->numRows-1);
	}
	
	/**
	 * Return the previous result.
	 *
	 * @return array
	 */
	public function previous() {
		return $this->seek($this->idx-1);
	}
	
	/**
	 * Return a given result.
	 *
	 * @param integer rownum
	 * @param integer mode
	 * @return array
	 */
	public function seek($rownum, $mode = ResultSet::SEEKMODE_ABSOLUTE) {
		// default to null if out-of-bounds
		if ($rownum >= $this->numRows) {
			return null;
		}
		// perform seek
		if ($rownum != $this->idx) {
			@mysqli_data_seek($this->result, $rownum);
		}
		
		// update idx
		$this->idx = $rownum+1;
		// fetch according to desired seek-mode
		switch ($this->mode) {
			case ResultSet::FETCHMODE_ASSOC:
				return $this->cur = @mysqli_fetch_assoc($this->result);
			case ResultSet::FETCHMODE_NUM:
			default:
				return $this->cur = @mysqli_fetch_row($this->result);
		}
	}
	
	/**
	 * Retrieve the number of rows in this result set.
	 *
	 * @return integer
	 */
	public function getNumRows() {
		return $this->numRows;
	}
	
	/**
	 * Get a column from the current row of the result set.
	 *
	 * @param integer|string column
	 * @return integer|float|double|string|object
	 */
	public function get($column) {
		if (!$this->cur) {
			$this->next();
		}
		
		return $this->cur[$column];
	}
}

?>
