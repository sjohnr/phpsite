<?php

/**
 * SessionFilter class.
 * Initialize the Session class. Handle session flashing.
 *
 * @author Stephen Riesenberg
 */
class SessionFilter extends Filter {
	public function execute($filterChain) {
		$session = Session::getInstance();
		
		// memorize flashed variables
		$names = array_keys($session->getAll('flash'));
		
		// continue
		$filterChain->execute();
		
		// remove memorized variables
		foreach ($names as $name) {
			$session->remove($name, 'flash');
		}
	}
}

?>
