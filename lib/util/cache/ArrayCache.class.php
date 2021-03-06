<?php

/**
 * ArrayCache class.
 *
 * An implementation of the cache class dealing specifically with php <code>array</code> data structures.
 * This class bypasses the read function in favor of using the <code>include</code> function.
 *
 * @author Stephen Riesenberg
 */
class ArrayCache extends FileCache {
	/**
	 * ArrayCache constructor.
	 *
	 * @param array parameters
	 */
	public function __construct($dir, $ext = '.php') {
		parent::__construct(array('dir' => $dir, 'ext' => $ext));
	}
	
	/**
	 * Set a value in the cache.
	 *
	 * @param string key
	 * @param string|array value
	 * @return boolean
	 */
	public function set($key, $value) {
		$data = array();
		
		$data[] = '<?php';
		$data[] = '// auto-generated by ArrayCache';
		$data[] = '// date: '.date('Y-m-d H:i:s');
		$data[] = '';
		$data[] = 'return '.var_export($value, true).';';
		$data[] = '';
		$data[] = '?>';
		
		return parent::set($key, implode("\n", $data));
	}
	
	/**
	 * Instead of reading a file, execute as php, and return the result.
	 *
	 * @param string path
	 * @param string file
	 * @return string|array
	 */
	protected function read($path, $file) {
		$data = include($path.'/'.$file);
		
		return $data;
	}
}
