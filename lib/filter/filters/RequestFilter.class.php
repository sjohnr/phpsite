<?php

/**
 * RequestFilter class.
 * Rewites the $_REQUEST superglobal using the routing functionality.
 *
 * @author Stephen Riesenberg
 */
class RequestFilter extends Filter {
	public function execute($filterChain) {
		$config = Config::getInstance()->get('routes');
		
		// configure routes
		$routes = array();
		foreach ($config as $key => $value) {
			$routes[$key] = $value['route'];
		}
		
		// find appropriate route for this request
		$route = Uri::connect($routes);
		
		// parse this route and associate with request
		$result = Uri::parse($config[$route]['route'], $config[$route]['defaults']);
		foreach ($result as $key => $value) {
			$_REQUEST[$key] = $value;
		}
		
		// continue filter chain execution
		$filterChain->execute();
	}
}

?>
