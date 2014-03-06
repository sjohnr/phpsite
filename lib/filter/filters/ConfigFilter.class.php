<?php

/**
 * ConfigFilter class.
 * Initialize the Config class.
 *
 * @author Stephen Riesenberg
 */
class ConfigFilter extends Filter {
	public function execute($filterChain) {
		$config = Config::getInstance();
		
		// start the timer
		if (IS_DEBUG) {
			$config->set('timer_start', $timer_start = microtime(true));
		}
		
		// build paths
		$config->add(array(
			// environment variables
			'root_dir'      => $root_dir      = ROOT_DIR,
			'app_name'      => $app_name      = APP_NAME,
			'environment'   => $environment   = ENVIRONMENT,
			'is_debug'      => $is_debug      = IS_DEBUG,
			
			// base directories
			'apps_dir'      => $apps_dir      = $root_dir.DIRECTORY_SEPARATOR.'apps',
			'cache_dir'     => $cache_dir     = $root_dir.DIRECTORY_SEPARATOR.'cache',
			'model_dir'     => $model_dir     = $root_dir.DIRECTORY_SEPARATOR.'model',
			'www_dir'       => $www_dir       = $root_dir.DIRECTORY_SEPARATOR.'www',
			
			// app directories
			'app_dir'       => $app_dir       = $apps_dir.DIRECTORY_SEPARATOR.$app_name,
			'lib_dir'       => $lib_dir       = $app_dir.DIRECTORY_SEPARATOR.'lib',
			'config_dir'    => $config_dir    = $app_dir.DIRECTORY_SEPARATOR.'config',
			'modules_dir'   => $modules_dir   = $app_dir.DIRECTORY_SEPARATOR.'modules',
			'templates_dir' => $templates_dir = $app_dir.DIRECTORY_SEPARATOR.'templates',
			
			// www directories
			'files_dir'     => $files_dir     = $www_dir.DIRECTORY_SEPARATOR.'files',
			'images_dir'    => $images_dir    = $www_dir.DIRECTORY_SEPARATOR.'images',
			'uploads_dir'   => $uploads_dir   = $www_dir.DIRECTORY_SEPARATOR.'uploads',
		));
		
		// gather global configs
		$configs = require($config_dir.DIRECTORY_SEPARATOR.'config.php');
		$config->add($configs);
		
		// dynamically add additional filters to filter chain
		$filters = $config->get('filters', array());
		foreach ($filters as $filterName => $filterConfig) {
			$className = $filterConfig['className'];
			$parameters = isset($filterConfig['parameters']) ? $filterConfig['parameters'] : array();
			$filter = new $className($parameters);
			$filterChain->addFilter($filter);
		}
		
		// continue
		$filterChain->execute();
		
		// stop the timer
		if (IS_DEBUG) {
			$config->set('timer_stop', $timer_stop = microtime(true));
			$config->set('timer_total', $timer_total = ($timer_stop - $timer_start));
		}
	}
}

?>
