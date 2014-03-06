<?php

/**
 * FileCache class.
 * An implementation of the cache class, using files to cache results.
 *
 * @author Stephen Riesenberg
 */
class FileCache extends Cache {
	/**
	 * FileCache constructor.
	 *
	 * @param array parameters
	 */
	public function __construct($parameters = array()) {
		if (!isset($parameters['dir'])) {
			throw new Exception('No directory specified for the cache.');
		}
		
		parent::__construct(array_merge(array('ext' => '.cache'), $parameters));
	}
	
	/**
	 * Get a value from the cache.
	 *
	 * @param string key
	 * @param string default
	 * @return string
	 */
	public function get($key, $default = null) {
		$result = $default;
		if ($this->has($key)) {
			$result = $this->read($this->getParameter('dir'), $key.$this->getParameter('ext'));
		}
		
		return $result;
	}
	
	/**
	 * Determine whether a values is in the cache.
	 *
	 * @param string key
	 * @return boolean
	 */
	public function has($key) {
		$dir = $this->getParameter('dir');
		$ext = $this->getParameter('ext');
		$file = $dir.'/'.$key.$ext;
		$timeout = $this->hasParameter('timeout') ? $this->getParameter('timeout') : null;
		
		$result = true;
		if (!file_exists($file)) {
			$result = false;
		} else if ($timeout != null && (time() - @filemtime($file) > $timeout)) {
			$result = false;
		}
		
		return $result;
	}
	
	/**
	 * Set a value in the cache.
	 *
	 * @param string key
	 * @param string data
	 * @return boolean
	 */
	public function set($key, $data) {
		$dir = $this->getParameter('dir');
		$ext = $this->getParameter('ext');
		$filename = $key.$ext;
		
		return $this->write($dir, $filename, $data);
	}
	
	/**
	 * Remove a value in the cache.
	 *
	 * @param string key
	 * @return boolean
	 */
	public function remove($key) {
		$dir = $this->getParameter('dir');
		$ext = $this->getParameter('ext');
		$file = $dir.'/'.$key.$ext;
		
		return @unlink($file);
	}
	
	/**
	 * Read a file.
	 *
	 * @param string path
	 * @param string file
	 * @return string
	 */
	protected function read($path, $file) {
		if (!$fp = @fopen($path.'/'.$file, 'rb')) {
			throw new Exception(sprintf('Unable to read cache file "%s".', $path));
		}
		
		@flock($fp, LOCK_SH);
		clearstatcache(); // because the filesize can be cached by PHP itself...
		$length = @filesize($path.'/'.$file);
		$data = '';
		if ($length) {
			$data = @fread($fp, $length);
		}
		@flock($fp, LOCK_UN);
		@fclose($fp);
		
		return $data;
	}
	
	/**
	 * Write a file.
	 *
	 * @param string path
	 * @param string file
	 * @param string data
	 * @return boolean
	 */
	protected function write($path, $file, $data) {
		$current_umask = umask();
		umask(0000);
		
		if (!is_dir($path)) {
			// create directory structure if needed
			mkdir($path, 0775, true);
		}
		
		if (!$fp = @fopen($path.'/'.$file, 'wb')) {
			throw new Exception(sprintf('Unable to write cache file "%s".', $path.'/'.$file));
		}
		
		@flock($fp, LOCK_EX);
		@fwrite($fp, $data);
		@flock($fp, LOCK_UN);
		@fclose($fp);
		
		// change file mode
		chmod($path.'/'.$file, 0775);
		umask($current_umask);
		
		return true;
	}
}

?>