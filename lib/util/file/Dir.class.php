<?php

/**
 * Directory class.
 *
 * @author Stephen Riesenberg <sjohnr@hotmail.com>
 */
class Dir {
	/**
	 * Read mode: file
	 *
	 * @var string
	 */
	const FILE_MODE = 'file';
	
	/**
	 * Read mode: directory
	 *
	 * @var string
	 */
	const DIRECTORY_MODE = 'dir';
	
	private $path;
	private $ext;
	
	/**
	 * Construct a Directory.
	 *
	 * @param string path
	 * @param string ext
	 */
	public function __construct($path, $ext = '') {
		$this->path = $path;
		$this->ext = $ext;
	}
	
	/**
	 * Clean a directory of a certain type of file.
	 *
	 * <b>Example:</b>
	 * <code>$dir = new Directory(ROOT_DIR . '/cache', '.cache'); $dir->clean();</code>
	 */
	public function clean() {
		$handle = opendir($this->path);
		while (($filename = readdir($handle)) !== false) {
			if (($filename == '.' || $filename == '..') || ($this->ext && strpos($filename, $this->ext) === false)) {
				continue;
			}
			
			unlink($this->path.'/'.$filename);
		}
		
		closedir($handle);
	}
	
	/**
	 * Read the contents of a directory, and return as an array.
	 *
	 * <b>Examples:</b>
	 * <code>$dir = new Directory(ROOT_DIR.'/mydir', '.js'); $dirs = $dir->read(Directory::DIRECTORY_MODE);</code>
	 * -> array(Directory, Directory, ...)
	 * <code>$dir = new Directory(ROOT_DIR.'/mydir', '.js'); $files = $dir->read(Directory::FILE_MODE);</code>
	 * -> array('file1' => '.../mydir/file1.js', 'file2' => '.../mydir/file2.js', ...)
	 *
	 * @return array
	 */
	public function read($mode = self::FILE_MODE) {
		$handle = opendir($this->path);
		$results = array();
		while (($filename = readdir($handle)) !== false) {
			if ($filename == '.' || $filename == '..') {
				continue;
			}
			
			switch ($mode) {
				case self::DIRECTORY_MODE:
					if (is_dir($this->path.'/'.$filename)) {
						$results[] = new Directory($this->path.'/'.$filename, $this->ext);
					}
				break;
				case self::FILE_MODE:
				default:
					if (strpos($filename, $ext) !== false) {
						$results[str_replace($ext, '', $filename)] = $this->path.'/'.$filename;
					}
				break;
			}
		}
		
		closedir($handle);
		
		return $results;
	}
}
