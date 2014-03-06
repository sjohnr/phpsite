<?php

/**
 * MyDB class.
 *
 * @author Stephen Riesenberg
 */
class MyDB {
	private static $conn = null;
	
	/**
	 * Database connection singleton.
	 *
	 */
	public static function getConnection() {
		if (self::$conn == null) {
			$config = Config::getInstance();
			$driver = $config->get('mydb_driver', 'mysql');
			switch ($driver) {
				case 'mysql':
				case 'mysqli':
					$conn = new MySQLConnection();
					break;
				default:
					throw new Exception(sprintf("Database not currently supported: ", MyDB_DRIVER));
			}
			
			$conn->connect(array(
				'hostspec' => $config->get('settings.database.mydb_hostspec', 'localhost'),
				'username' => $config->get('settings.database.mydb_username', 'root'),
				'password' => $config->get('settings.database.mydb_password', ''),
				'database' => $config->get('settings.database.mydb_database', ''),
				'port'     => $config->get('settings.database.mydb_port', 3306),
				'socket'   => $config->get('settings.database.mydb_socket', ''),
			));
			
			self::$conn = $conn;
		}
		
		return self::$conn;
	}
}

?>
