<?php

/**
 * Logger class.
 * This class is used for default logging functionality.
 *
 * @author Stephen Riesenberg
 */
class Logger {
	const NONE    = 1;
	const ERROR   = 2;
	const WARNING = 3;
	const NOTICE  = 4;
	const DEBUG   = 5;
	const INFO    = 6;
	
	/**
	 * Singleton instance.
	 */
	private static $instance = null;
	
	/**
	 * Current logging level.
	 */
	private $level = Logger::INFO;
	
	/**
	 * Error logs.
	 */
	private $logs = array();
	
	/**
	 * Constructor.
	 *
	 */
	private function __construct() {
		
	}
	
	/**
	 * Logger singleton.
	 *
	 * @return object
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new Logger();
		}
		
		return self::$instance;
	}
	
	/**
	 * Initialize the logger.
	 *
	 * @param int level
	 */
	public function setLogLevel($level = Logger::INFO) {
		$this->level = $level;
	}
	
	/**
	 * Retrieve priority for the given log level.
	 *
	 * @param int level
	 * @return string
	 */
	 public static function getPriority($level = Logger::INFO) {
		switch ($level) {
			case Logger::NONE:
				return "NONE";
			case Logger::ERROR:
				return "ERROR";
			case Logger::WARNING:
				return "WARNING";
			case Logger::NOTICE:
				return "NOTICE";
			case Logger::DEBUG:
				return "DEBUG";
			case Logger::INFO:
				return "INFO";
			default:
				throw new Exception(sprintf("Invalid log level: %s", $level));
		}
	}
	
	/**
	 * Log an error message.
	 *
	 * @param string message
	 * @param int level
	 */
	public function log($message, $level = Logger::INFO) {
		if ($this->level >= $level) {
			// generate log message
			$log = sprintf("%s [%s] %s", strftime("%b %d %H:%M:%S"), Logger::getPriority($level), $message);
			$this->logs[] = $log;
			
			// write log to file
			$handle = @fopen("logs/default.log", "a");
			@flock($handle, LOCK_EX);
			@fwrite($handle, $log."\n");
			@flock($handle, LOCK_UN);
			@fclose($handle);
		}
	}
	
	/**
	 * Log an ERROR message.
	 *
	 * @param string message
	 */
	public function err($message) {
		$this->log($message, Logger::ERROR);
	}
	
	/**
	 * Determine if the logging level is ERROR or above.
	 *
	 * @return boolean
	 */
	public function isErrorEnabled() {
		return ($this->level >= Logger::ERROR);
	}
	
	/**
	 * Log a WARNING message.
	 *
	 * @param string message
	 */
	public function warn($message) {
		$this->log($message, Logger::WARNING);
	}
	
	/**
	 * Determine if the logging level is WARNING or above.
	 *
	 * @return boolean
	 */
	public function isWarningEnabled() {
		return ($this->level >= Logger::WARNING);
	}
	
	/**
	 * Log a NOTICE message.
	 *
	 * @param string message
	 */
	public function notice($message) {
		$this->log($message, Logger::NOTICE);
	}
	
	/**
	 * Determine if the logging level is NOTICE or above.
	 *
	 * @return boolean
	 */
	public function isNoticeEnabled() {
		return ($this->level >= Logger::NOTICE);
	}
	
	/**
	 * Log a DEBUG message.
	 *
	 * @param string message
	 */
	public function debug($message) {
		$this->log($message, Logger::DEBUG);
	}
	
	/**
	 * Determine if the logging level is DEBUG or above.
	 *
	 * @return boolean
	 */
	public function isDebugEnabled() {
		return ($this->level >= Logger::DEBUG);
	}
	
	/**
	 * Log an INFO message.
	 *
	 * @param string message
	 */
	public function info($message) {
		$this->log($message, Logger::INFO);
	}
	
	/**
	 * Determine if the logging level is INFO or above.
	 *
	 * @return boolean
	 */
	public function isInfoEnabled() {
		return ($this->level >= Logger::INFO);
	}
	
	/**
	 * Retrieve a printout of the log.
	 *
	 * @return string
	 */
	public function getLog() {
		return implode("\n", $this->logs);
	}
	
	/**
	 * Retrieve the logs.
	 *
	 * @return array
	 */
	public function getLogs() {
		return $this->logs;
	}
}

?>
