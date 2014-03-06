<?php

/**
 * Uri class.
 * Computes values related to the request uri, and has shortcuts to the routing library.
 *
 * @author Stephen Riesenberg
 */
class Uri {
	/**
	 * Use the request uri and a given route to get an array of parameters.
	 *
	 */
	public static function parse($route, $defaults = array()) {
		return Routing::parse(Server::getCurrentUri(), $route, $defaults);
	}
	
	/**
	 * Use the request uri and a given set of routes to determine the matching route.
	 *
	 */
	public static function connect($routes) {
		return Routing::connect(Server::getCurrentUri(), $routes);
	}
	
	/**
	 * Use the request uri for decomposition.
	 *
	 */
	public static function decompose() {
		return Routing::decompose(Server::getCurrentUri());
	}
}

?>