<?php

/**
 * RedirectException class.
 * Represents a redirect in the system. Caught and handled silently.
 *
 * @author Stephen Riesenberg
 */
class RedirectException extends Exception {
	private $location;
	
	public function __construct($message, $location = '/') {
		parent::__construct($message, 0);
		$this->location = $location;
	}
	
	public function getLocation() {
		return $this->location;
	}
}

?>
