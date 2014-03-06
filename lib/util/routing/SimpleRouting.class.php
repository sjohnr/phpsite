<?php

/**
 * SimpleRouting class.
 * A very simple implementation of routing functionality.
 *
 * @author Stephen Riesenberg
 */
class SimpleRouting {
	/**
	 * Parse the current uri, using a simple routing scheme.
	 *
	 * with http://www.mydomain.com/module/home/action/inbox/view/flat/page/2
	 * <code>echo SimpleRouting::parse();</code>
	 * -> array(
	 *   'module' => 'home',
	 *   'action' => 'inbox',
	 *   'view' => 'flat',
	 *   'page' => 2
	 * )
	 *
	 * @return array
	 */
	public static function parse() {
		// strip script path from current request uri
		$uri = str_replace(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['REQUEST_URI']);
		
		// standardize uri with slashes
		if (strpos($uri, '?') !== false || strpos($uri, '&') !== false || strpos($uri, '=') !== false) {
			$uri = str_replace(array('?', '&', '='), '/', $uri);
		}
		
		// break up uri
		$elements = explode('/', substr($uri, 1));
		
		// build parameters array
		$params = array();
		for ($i = 0; $i < count($elements) - 1; $i += 2) {
			$params[$elements[$i]] = isset($elements[$i+1]) ? $elements[$i+1] : '';
		}
		
		return $params;
	}
	
	/**
	 * Generate a uri, given an internal uri, using a simple routing scheme.
	 *
	 * <code>echo SimpleRouting::generate('/home/inbox?view=flat&page=2');</code>
	 * -> /home/inbox/view/flat/page/2
	 *
	 * @param string uri
	 * @return string
	 */
	public static function generate($uri) {
		return str_replace(array('?', '&', '='), '/', $uri);
	}
}

?>