<?php

/**
 * Routing class.
 * Full-featured implementation of routing, allows routing based on routing rules.
 *
 * @author Stephen Riesenberg
 */
class Routing {
	/**
	 * Build an array from the uri.
	 *
	 * <b>Examples:</b>
	 * <code>echo Routing::decompose('/home');</code>
	 * -> array(
	 *			0 => 'home',
	 *		)
	 *
	 * <code>echo Routing::decompose('/home/foo/bar/string?attribute=value&key=value');</code>
	 * -> array(
	 *			0 => 'home',
	 *			1 => 'foo',
	 *			2 => 'bar',
	 *			3 => 'string',
	 *			4 => 'attribute',
	 *			5 => 'value',
	 *			6 => 'key',
	 *			7 => 'value',
	 *		)
	 *
	 * @param string uri
	 * @return array
	 */
	public static function decompose($uri) {
		if (strlen($uri) > 0 && $uri[0] != '/') {
			$uri = '/'.$uri;
		}
		if (strpos($uri, '?') !== false || strpos($uri, '&') !== false || strpos($uri, '=') !== false) {
			$uri = str_replace(array('?', '&', '='), '/', $uri);
		}
		
		return strlen($uri) == 1 ? array() : explode('/', substr($uri, 1));
	}
	
	/**
	 * Parses a query string from a given uri into a parameter array.
	 *
	 * @param string uri
	 * @return array
	 */
	public static function parametize($uri) {
		// parse query string into parameters
		$params = array();
		if (strpos($uri, '?') !== false) {
			foreach (explode('&', substr($uri, strpos($uri, '?')+1)) as $kvpair) {
				list($key, $value) = explode('=', $kvpair);
				
				$params[$key] = $value;
			}
		}
		
		return $params;
	}
	
	/**
	 * Parse a uri string into a parameter array based on a routing rule.
	 *
	 * <b>Examples:</b>
	 * <code>Routing::parse('/home', '/:script');</code>
	 * -> array('script' => 'home')
	 *
	 * <code>Routing::parse('/home/foo.bar/key/value', '/:script/:mode/*');</code>
	 * -> array('script' => 'home', 'mode' => 'foo.bar', 'key' => 'value')
	 *
	 * @param string uri
	 * @param string route
	 * @param array defaults
	 * @return array
	 */
	public static function parse($uri, $route, $defaults = array()) {
		// decompose the uri and routing rule
		$elements = self::decompose($uri);
		$components = self::decompose($route);
		
		// build the resulting array
		$params = array();
		for ($i = 0; $i < count($components); $i++) {
			// special case - decoration (eg. :param.html)
			if (strpos($components[$i], '.') !== false) {
				list($components[$i],) = explode('.', $components[$i]);
			}
			if (isset($elements[$i]) && strpos($elements[$i], '.') !== false) {
				list($elements[$i],) = explode('.', $elements[$i]);
			}
			
			// test the first character
			switch (substr($components[$i], 0, 1)) {
				case ':':
					$param = substr($components[$i], 1);
					// match an invisible parameter
					if (isset($elements[$i])) {
						$params[$param] = $elements[$i];
					} else if (isset($defaults[$name])) {
						$params[$param] = $defaults[$param];
						unset($defaults[$param]);
					}
					
					break;
				case '*':
					// match the rest of the parameters
					for ($j = $i; $j < count($elements); $j++) {
						$params[$elements[$j]] = $elements[++$j];
					}
					
					break;
				default:
					// don't match a string
					break;
			}
		}
		
		return array_merge($defaults, $params);
	}
	
	/**
	 * Generate a uri, given an internal uri, a route, and default parameters.
	 *
	 * <b>Example:</b>
	 * <code>echo Routing::generate('/home/inbox?view=flat&page=2', '/:module/:action/:view/:page');</code>
	 * -> /home/inbox/flat/2
	 *
	 * @param string uri
	 * @param string route
	 * @param array defaults
	 */
	public static function generate($uri, $route, $defaults = array()) {
		// decompose the uri and routing rule
		$elements   = self::decompose($uri);
		$components = self::decompose($route);
		$params     = self::parametize($uri);
		
		// build the resulting array to be converted to a url
		$ar = array();
		for ($i = 0; $i < count($components); $i++) {
			// special case - decoration (eg. :param.html)
			$ext = null;
			if (strpos($components[$i], '.') !== false) {
				list($components[$i], $ext) = explode('.', $components[$i]);
			}
			
			// test the first character
			switch (substr($components[$i], 0, 1)) {
				case ':':
					// match an invisible parameter
					$param = substr($components[$i], 1);
					if (isset($elements[$i])) {
						// we guess the parameter from the uri elements array
						$ar[] = $elements[$i];
					} else if (isset($params[$param])) {
						// we know the parameter from the query string
						$ar[] = $params[$param];
						unset($params[$param]);
					} else if (isset($defaults[$param])) {
						// we know the parameter from defaults
						$ar[] = $defaults[$param];
					}
					
					break;
				case '*':
					// match the rest of the parameters
					foreach ($params as $key => $value) {
						$ar[] = $key;
						$ar[] = $value;
					}
					
					break;
				default:
					// match an explicit string
					$ar[] = $components[$i];
					
					break;
			}
			
			// special case - decoration (eg. :param.html)
			if ($ext) {
				// append decoration to last element added
				$ar[count($ar) - 1] .= '.'.$ext;
			}
		}
		
		return '/'.implode('/', $ar);
	}
	
	/**
	 * Match a given uri to a route, given a list of routes.
	 *
	 * @param string uri
	 * @param array routes
	 * @return string
	 */
	public static function connect($uri, $routes = array()) {
		if (substr($uri, 0, 1) == '@') {
			return $routes[substr($uri, 1)];
		}
		
		foreach ($routes as $name => $route) {
			// check the uri against the route
			if (self::check($uri, $route)) {
				return $name;
			}
		}
		
		return null;
	}
	
	/**
	 * Check a given uri against a route.
	 *
	 * @param string uri
	 * @param string route
	 */
	private static function check($uri, $route) {
		// decompose the uri and route
		$elements = self::decompose($uri);
		$components = self::decompose($route);
		
		for ($i = 0; $i < count($components); $i++) {
			if (substr($components[$i], 0, 1) == '*') {
				return true;
			} else if (!isset($elements[$i])) {
				return false;
			} else if (substr($components[$i], 0, 1) == ':') {
				continue;
			} else if ($components[$i] != $elements[$i]) {
				return false;
			}
		}
		
		if (count($elements) == count($components)) {
			return true;
		}
		
		return false;
	}
}

?>