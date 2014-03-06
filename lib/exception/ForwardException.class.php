<?php

/**
 * ForwardException class.
 * Represents an internal forward in the system. Caught and handled silently.
 *
 * @author Stephen Riesenberg
 */
class ForwardException extends Exception {
	private $moduleName;
	private $actionName;
	private $vars;
	
	public function __construct($message, $moduleName = 'default', $actionName = 'index', $vars = array()) {
		parent::__construct($message, 0);
		$this->moduleName = $moduleName;
		$this->actionName = $actionName;
		$this->vars = $vars;
	}
	
	public function getModuleName() {
		return $this->moduleName;
	}
	
	public function getActionName() {
		return $this->actionName;
	}
	
	public function getVars() {
		return $this->vars;
	}
}

?>
