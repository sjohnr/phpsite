<?php

/**
 * AuthenticationFilter class.
 *
 * @author Stephen Riesenberg
 */
class AuthenticationFilter extends Filter {
	private $parameters = null;
	
	public function __construct($parameters = array()) {
		$this->parameters = $parameters;
	}
	
	public function execute($filterChain) {
		$config = Config::getInstance();
		$session = Session::getInstance();
		$controller = Controller::getInstance();
		$request = Request::getInstance();
		
		$module = $request->get('module');
		$action = $request->get('action');
		
		$auth = true;
		foreach ($this->parameters['excluded_modules'] as $exclusion) {
			if ($module == $exclusion) {
				$auth = false;
				break;
			}
		}
		foreach ($this->parameters['excluded_actions'] as $exclusion) {
			if ($module == $exclusion[0] && $action == $exclusion[1]) {
				$auth = false;
				break;
			}
		}
		
		if ($auth && $config->get('is_authentication_enabled', false)) {
			if ($session->get('is_logged_in', false) === false) {
				$request->set('module', 'default');
				$request->set('action', 'secure');
			}
		}
		
		// continue filter chain execution
		$filterChain->execute();
	}
}

?>
