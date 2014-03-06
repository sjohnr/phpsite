<?php

/**
 * ControllerException class.
 * Represents a controller exception in the system. Indicates HTTP <code>500</code> error.
 *
 * @author Stephen Riesenberg
 */
class ControllerException extends Exception {
	public function __construct($message, $code = 0) {
		parent::__construct($message, $code);
	}
}

?>
