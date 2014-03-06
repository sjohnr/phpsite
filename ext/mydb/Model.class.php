<?php

/**
 * Model class.
 * Represents an instance of a CRUD Model,
 * with functionality to manipulate a single record at a time, or query a table with MQL.
 *
 * @author Stephen Riesenberg
 */
class Model {
	private $table;
	private $db;
	
	/**
	 * Model constructor.
	 *
	 */
	public function __construct($table) {
		$this->table = $table;
		$this->db    = MyDB::getConnection();
	}
	
	/**
	 * INSERT a record in the database.
	 *
	 * @param array params
	 * @return int
	 */
	public function create($params) {
		// generate columns and values
		$columns = array();
		$values  = array();
		foreach ($params as $key => $value) {
			$columns[] = "{$key}";
			$values[] = ":{$key}";
		}
		
		// generate strings
		$columns = implode(", ", $columns);
		$values  = implode(", ", $values);
		
		// generate sql
		$sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
		
		// execute query
		$this->db->prepare($sql)->update($params);
		
		return $this->db->getGeneratedId();
	}
	
	/**
	 * SELECT a record from the database by primary key.
	 *
	 * @param integer id
	 * @return object
	 */
	public function retrieve($id) {
		// generate sql
		$sql = "SELECT {$this->table}.* FROM {$this->table} WHERE {$this->table}_id = :id";
		
		// execute query
		$result = $this->db->prepare($sql)->query(array('id' => $id));
		$record = $result->next();
		
		return $record;
	}
	
	/**
	 * UPDATE a record in the database.
	 *
	 * @param integer id
	 * @param array params
	 * @return int
	 */
	public function update($id, $params = array()) {
		// generate set clauses
		$sets = array();
		foreach ($params as $key => $value) {
			$sets[] = "{$this->table}.{$key} = :{$key}";
		}
		
		// generate set clause
		$set = implode(", ", $sets);
		
		// generate sql
		$sql = "UPDATE {$this->table} SET {$set} WHERE {$this->table}.{$this->table}_id = :id";
		
		// execute query
		$this->db->prepare($sql)->update(array_merge($params, array('id' => $id)));
	}
	
	/**
	 * DELETE a record in the database.
	 *
	 * @param integer id
	 */
	public function delete($id) {
		$sql = "DELETE FROM {$this->table} WHERE {$this->table}.{$this->table}_id = :id";
		
		// execute query
		$this->db->prepare($sql)->update(array('id' => $id));
	}
	
	/**
	 * SELECT several records from the db, and convert them into an array.
	 *
	 * @param string sql
	 * @param array params
	 */
	public function query($sql, $params = array()) {
		// execute query
		$result = $this->db->prepare($sql)->query($params);
		
		// build list
		$records = array();
		while (($record = $result->next()) != null) {
			$records[] = $record;
		}
		
		return $records;
	}
	
	/**
	 * Issue an INSERT or DELETE statement against the db.
	 *
	 * @param string sql
	 * @param array params
	 * @return int
	 */
	public function execute($sql, $params = array()) {
		// execute query
		return $this->db->prepare($sql)->update($params);
	}
}

?>
