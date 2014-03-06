<?php

/**
 * ExceptionFilter class.
 * Executes the main request, by initiating the module/action indicated by the request.
 *
 * @author Stephen Riesenberg
 */
class ExecutionFilter extends Filter {
	public function execute($filterChain) {
		$controller = Controller::getInstance();
		$request    = Request::getInstance();
		$response   = Response::getInstance();
		$config     = Config::getInstance();
		
		// gather request variables
		$moduleName = $request->get('module');
		$actionName = $request->get('action');
		
		// gather module configs
		if (file_exists($config->get('modules_dir').'/'.$moduleName.'/config.php')) {
			$configs = require($config->get('modules_dir').'/'.$moduleName.'/config.php');
			$config->add($configs);
		}
		
		// gather action configs
		if (file_exists($config->get('modules_dir').'/'.$moduleName.'/config/'.$actionName.'.php')) {
			$configs = require($config->get('modules_dir').'/'.$moduleName.'/config/'.$actionName.'.php');
			$config->add($configs);
		}
		
		// perform internal forward to dispatch the action
		$controller->forward($moduleName, $actionName);
		
		// use the final action to determine response
		$actionInstance = $controller->getActionStack()->peek();
		$vars = array_merge(array(
			'module'   => $actionInstance->getModuleName(),
			'action'   => $actionInstance->getActionName(),
			'template' => $actionInstance->getTemplate(),
			'view'     => $actionInstance->getView(),
			'errors'   => $actionInstance->getErrors(),
		), $actionInstance->getVars());
		
		// memorize response variables
		$response->add($vars);
		
		// continue filter chain execution
		$filterChain->execute();
	}
}

?>
