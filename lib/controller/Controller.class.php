<?php

/**
 * Controller class.
 * Dispatches and bootstraps the request.
 *
 * @author Stephen Riesenberg
 */
class Controller {
	/**
	 * Maximum number of forwards allowed.
	 */
	const MAX_FORWARDS = 5;
	
	/**
	 * Singleton instance.
	 */
	private static $instance = null;
	
	/**
	 * The actions stack, tracks all actions that have been executed.
	 *
	 */
	private $actionStack;
	
	/**
	 * Controller singleton.
	 *
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new Controller();
		}
		
		return self::$instance;
	}
	
	/**
	 * Constructor.
	 *
	 */
	private function __construct() {
		$this->actionStack = new Stack();
	}
	
	/**
	 * Get the action stack.
	 *
	 * @return object
	 */
	public function getActionStack() {
		return $this->actionStack;
	}
	
	/**
	 * Initialize and execute the request.
	 *
	 */
	public function dispatch() {
		// initialize the filter chain
		$filterChain = new FilterChain();
		$filterChain->addFilter(new ConfigFilter());
		
		// execute the filter chain
		$filterChain->execute();
	}
	
	/**
	 * Internally forward to a new action.
	 *
	 * @param string moduleName
	 * @param string actionName
	 */
	public function forward($moduleName, $actionName, $vars = null) {
		$config = Config::getInstance();
		
		// check for too many forwards
		if ($this->actionStack->size() > Controller::MAX_FORWARDS) {
			throw new ControllerException(sprintf("Too many forwards: %s", $this->actionStack->size()));
		}
		
		// default vars to $_REQUEST
		if ($vars == null) {
			$vars = Request::getInstance()->getAll();
		}
		
		// create action instance
		if (file_exists($config->get('modules_dir').'/'.$moduleName.'/'.$moduleName.'_actions.php')) {
			require_once($config->get('modules_dir').'/'.$moduleName.'/'.$moduleName.'_actions.php');
			$className = $moduleName.'_actions';
		} else if (file_exists($config->get('modules_dir').'/'.$moduleName.'/actions/'.$actionName.'.php')) {
			require_once($config->get('modules_dir').'/'.$moduleName.'/actions/'.$actionName.'.php');
			$className = $moduleName.'_'.$actionName;
		} else {
			$this->forward('default', 'error404');
			return;
		}
		
		$actionInstance = new $className;
		$this->actionStack->push($actionInstance);
		
		// initialize the action
		$actionInstance->initialize($moduleName, $actionName, $vars);
		
		try {
			// pre execute
			$actionInstance->preExecute();
			
			// execute the action
			if ($actionInstance->validate()) {
				$actionInstance->execute();
			} else {
				$actionInstance->handleError();
			}
			
			// post execute
			$actionInstance->postExecute();
		} catch (ForwardException $ex) {
			$this->forward($ex->getModuleName(), $ex->getActionName(), $ex->getVars());
		} catch (RedirectException $ex) {
			$this->redirect($ex->getLocation());
		}
	}

	/**
	 * Redirect to a new url.
	 *
	 * @param string location
	 */
	public function redirect($location) {
		header('Location: '.$location);
	}
}

?>
