<?php

/**
 * Action base class.
 *
 * @author Stephen Riesenberg
 */
abstract class Action {
	/**
	 * Templating variables.
	 */
	private $vars = array();
	
	/**
	 * Errors array.
	 */
	private $errors = array();
	
	/**
	 * Module name of this action instance.
	 */
	private $moduleName;
	
	/**
	 * Action name of this action instance.
	 */
	private $actionName;
	
	/**
	 * Template name of this action instance (defaults to actionName).
	 */
	private $template;
	
	/**
	 * View name of this action instance (defaults to "").
	 */
	private $view = "";
	
	/**
	 * Request variables.
	 */
	private $request;
	
	/**
	 * Execute the action.
	 *
	 */
	public abstract function execute();
	
	/**
	 * Initialize this action instance.
	 *
	 */
	public function initialize($moduleName, $actionName, $vars = array()) {
		$this->moduleName = $moduleName;
		$this->actionName = $actionName;
		$this->template   = $actionName;
		$this->view       = "";
		$this->request    = new Map($vars);
	}
	
	/**
	 * Validate the action, returns true by default.
	 *
	 * @return boolean
	 */
	public function validate() {
		return true;
	}
	
	/**
	 * Perform error handling, sets the "Error" view by default.
	 *
	 */
	public function handleError() {
		$this->setView("Error");
	}
	
	/**
	 * Execute pre-action.
	 *
	 */
	public function preExecute() {
		
	}
	
	/**
	 * Execute post-action.
	 *
	 */
	public function postExecute() {
		
	}
	
	/**
	 * Internally forward to the given module/action.
	 *
	 * @param string moduleName
	 * @param string actionName
	 * @param array vars
	 *
	 * @throws ForwardException
	 */
	public function forward($moduleName, $actionName, $vars = array()) {
		throw new ForwardException(sprintf("Forwarding to: %s/%s", $moduleName, $actionName), $moduleName, $actionName, $vars);
	}
	
	/**
	 * Internally forward to the given module/action, if a condition holds true.
	 *
	 * @param boolean condition
	 * @param string moduleName
	 * @param string actionName
	 *
	 * @throws ForwardException
	 */
	public function forwardIf($condition, $moduleName, $actionName) {
		if ($condition) {
			$this->forward($moduleName, $actionName);
		}
	}
	
	/**
	 * Internally forward to the given module/action, if a condition does not hold true.
	 *
	 * @param boolean condition
	 * @param string moduleName
	 * @param string actionName
	 *
	 * @throws ForwardException
	 */
	public function forwardUnless($condition, $moduleName, $actionName) {
		if (!$condition) {
			$this->forward($moduleName, $actionName);
		}
	}
	
	/**
	 * Internally forward to the 404 page.
	 *
	 * @throws ForwardException
	 */
	public function forward404() {
		$this->forward('default', 'error404');
	}
	
	/**
	 * Internally forward to the 404 page, if a condition holds true.
	 *
	 * @param boolean condition
	 *
	 * @throws ForwardException
	 */
	public function forward404If($condition) {
		if ($condition) {
			$this->forward404();
		}
	}
	
	/**
	 * Internally forward to the 404 page, if a condition does not hold true.
	 *
	 * @param boolean condition
	 *
	 * @throws ForwardException
	 */
	public function forward404Unless($condition) {
		if (!$condition) {
			$this->forward404();
		}
	}
	
	/**
	 * Redirect to the given location.
	 *
	 * @param string location
	 *
	 * @throws RedirectException
	 */
	public function redirect($location) {
		throw new RedirectException(sprintf("Redirecting to: %s", $location), $location);
	}
	
	/**
	 * Redirect to the given location, if a condition holds true.
	 *
	 * @param boolean condition
	 * @param string location
	 *
	 * @throws RedirectException
	 */
	public function redirectIf($condition, $location) {
		if ($condition) {
			$this->redirect($moduleName.'/'.$actionName);
		}
	}
	
	/**
	 * Redirect to the given location, if a condition does not hold true.
	 *
	 * @param boolean condition
	 * @param string location
	 *
	 * @throws RedirectException
	 */
	public function redirectUnless($condition, $location) {
		if (!$condition) {
			$this->redirect($moduleName.'/'.$actionName);
		}
	}
	
	/**
	 * Get the moduleName.
	 *
	 * @return string
	 */
	public function getModuleName() {
		return $this->moduleName;
	}
	
	/**
	 * Get the actionName.
	 *
	 * @return string
	 */
	public function getActionName() {
		return $this->actionName;
	}
	
	/**
	 * Get a request parameter, defaults to null.
	 *
	 * @return string|array
	 */
	public function getRequestParameter($name, $default = null) {
		return $this->request->get($name, $default);
	}
	
	/**
	 * Determine if a parameter is present in the request.
	 *
	 * @return boolean
	 */
	public function hasRequestParameter($name) {
		return $this->request->has($name);
	}
	
	/**
	 * Set the template to render, upon completion of the action.
	 *
	 * @param string template
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}
	
	/**
	 * Get the template.
	 *
	 * @return string
	 */
	public function getTemplate() {
		return $this->template;
	}
	
	/**
	 * Set the view to render, upon completion of the action.
	 *
	 * @param string view
	 */
	public function setView($view) {
		$this->view = $view;
	}
	
	/**
	 * Get the view.
	 *
	 * @return string
	 */
	public function getView() {
		return $this->view;
	}
	
	/**
	 * Set an error message.
	 *
	 * @param string name
	 * @param string error
	 */
	public function setError($name, $error) {
		$this->errors[$name] = $error;
	}
	
	/**
	 * Get the errors array.
	 *
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * Set a templating variable.
	 *
	 * @param string name
	 * @param string|array|object obj
	 */
	public function setVar($name, $obj) {
		$this->vars[$name] = $obj;
	}
	
	/**
	 * Get a templating variable.
	 *
	 * @param string name
	 * @param string|array|object default
	 * @return string|array|object
	 */
	public function getVar($name, $default = null) {
		return isset($this->vars[$name]) ? $this->vars[$name] : $default;
	}
	
	/**
	 * Get the variables array.
	 *
	 * @return array
	 */
	public function getVars() {
		return $this->vars;
	}
}

?>
