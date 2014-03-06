<?php

/**
 * Includes and captures a component.
 *
 * @param string path
 * @param array vars
 * @return string
 */
function get_component($path, $vars = array()) {
	$controller = Controller::getInstance();
	$config     = Config::getInstance();
	$request    = Request::getInstance();
	$response   = Response::getInstance();
	
	$params = Routing::parse($path, '/:module/:action');
	$moduleName = $params['module'];
	$actionName = $params['action'];
	
	$controller->forward($moduleName, $actionName, $vars);
	$size = $controller->getActionStack()->size();
	$actionInstance = $controller->getActionStack()->pop();
	$module         = $actionInstance->getModuleName();
	$action         = $actionInstance->getActionName();
	$template       = $actionInstance->getTemplate();
	$view           = $actionInstance->getView();
	$errors         = $actionInstance->getErrors();
	
	// pop off any other stack entries
	while ($size > 1) {
		$controller->getActionStack()->pop();
		$size--;
	}
	
	ob_start();
	extract($actionInstance->getVars());
	include($config->get('modules_dir').'/'.$module.'/templates/'.$template.$view.'.php');
	
	return ob_get_clean();
}

/**
 * Outputs a component.
 *
 * @param string path
 * @param array vars
 * @see get_component
 */
function include_component($path, $vars = array()) {
	echo get_component($path, $vars);
}

/**
 * Includes and captures a template fragment (stub).
 *
 * @param string path
 * @param array vars
 * @return string
 */
function get_stub($path, $vars = array()) {
	$config   = Config::getInstance();
	$request  = Request::getInstance();
	$response = Response::getInstance();
	
	ob_start();
	extract($vars);
	if (file_exists($config->get('templates_dir').'/'.$path.'.php')) {
		include($config->get('templates_dir').'/'.$path.'.php');
	} else {
		$params = Routing::parse($path, '/:module/:action');
		include($config->get('modules_dir').'/'.$params['module'].'/templates/'.$params['action'].'.php');
	}
	
	return ob_get_clean();
}

/**
 * Outputs a template fragment (stub).
 *
 * @param string path
 * @param array vars
 * @see get_stub
 */
function include_stub($path, $vars = array()) {
	echo get_stub($path, $vars);
}

/**
 * Begin capturing a slot, preparing to store the result using given name.
 *
 * @param string name
 */
function slot($name) {
	$response = Response::getInstance();
	
	$slots = $response->get('slots', array(), 'view');
	$slotNames = $response->get('slot_names', array(), 'view');
	$slots[$name] = '';
	$slotNames[] = $name;
	
	$response->set('slots', $slots, 'view');
	$response->set('slot_names', $slotNames, 'view');
	
	ob_start();
}

/**
 * Stop the capture, and save the slot.
 *
 * @see slot
 */
function end_slot() {
	$response = Response::getInstance();
	
	$content = ob_get_clean();
	$slots = $response->get('slots', array(), 'view');
	$slotNames = $response->get('slot_names', array(), 'view');
	$name = array_pop($slotNames);
	$slots[$name] = $content;
	
	$response->set('slots', $slots, 'view');
	$response->set('slot_names', $slotNames, 'view');
}

/**
 * Retrieve a slot by name.
 *
 * @param string name
 * @return string
 * @see slot
 * @see end_slot
 */
function get_slot($name) {
	$response = Response::getInstance();
	
	$slots = $response->get('slots', array(), 'view');
	
	return (isset($slots[$name])) ? $slots[$name] : '';
}

/**
 * Output a slot by name.
 *
 * @param string name
 * @see get_slot
 */
function include_slot($name) {
	echo get_slot($name);
}

/**
 * Determine the existence of a slot.
 *
 * @param string name
 */
function has_slot($name) {
	$response = Response::getInstance();
	
	$slots = $response->get('slots', array(), 'view');
	
	return (isset($slots[$name]));
}

/**
 * Begin capturing a cache entry.
 *
 * @param string key
 * @param string type
 */
function cache($key, $type = 'html') {
	$response = Response::getInstance();
	
	$cacheNames = $response->get('cache_names', array(), 'cache');
	$cacheTypes = $response->get('cache_types', array(), 'cache');
	$cacheNames[] = $key;
	$cacheTypes[] = $type;
	
	$response->set('cache_names', $cacheNames, 'cache');
	$response->set('cache_types', $cacheTypes, 'cache');
	
	ob_start();
}

/**
 * Capture a cache element.
 *
 */
function end_cache() {
	$response = Response::getInstance();
	$config = Config::getInstance();
	
	$content = ob_get_clean();
	$cacheNames = $response->get('cache_names', array(), 'cache');
	$cacheTypes = $response->get('cache_types', array(), 'cache');
	$key = array_pop($cacheNames);
	$type = array_pop($cacheTypes);
	$cache = new FileCache(array(
		'dir' => $config->get('cache_dir').'/templates',
		'ext' => '.'.$type,
	));
	
	$cache->set($key, $content);
}

/**
 * Get the cache content for a given cache entry.
 *
 * @param string key
 * @param string type
 * @return string
 */
function get_cache($key, $type = 'html') {
	$config = Config::getInstance();
	
	$cache = new FileCache(array(
		'dir' => $config->get('cache_dir').'/templates',
		'ext' => '.'.$type,
	));
	
	return $cache->has($key) ? $cache->get($key) : '';
}

/**
 * Output a the content for a given cache entry.
 *
 * @param string key
 * @param string type
 * @see get_cache
 */
function include_cache($key, $type = 'html') {
	echo get_cache($key, $type);
}

/**
 * Determine if a cache entry exists and is usable.
 *
 * @param string key
 * @param string type
 * @return boolean
 */
function has_cache($key, $type = 'html') {
	$config = Config::getInstance();
	
	$cache = new FileCache(array(
		'dir' => $config->get('cache_dir').'/templates',
		'ext' => '.'.$type,
	));
	
	return $cache->has($key);
}

?>
