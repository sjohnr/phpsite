<?php

/**
 * ResponseFilter class.
 * Wraps the ExecutionFilter, capturing the response and generating wrapped response.
 *
 * @author Stephen Riesenberg
 */
class ResponseFilter extends Filter {
	public function execute($filterChain) {
		// initialize response
		$config = Config::getInstance();
		$request = Request::getInstance();
		$response = Response::getInstance();
		
		// execute the request
		$filterChain->execute();
		
		// apply response variables
		foreach ($config->get('response', array()) as $key => $value) {
			$response->set($key, $value);
		}
		
		// include and capture the content
		ob_start();
		extract($response->getAll());
		include($config->get('modules_dir').'/'.$response->get('module').'/templates/'.$response->get('template').$response->get('view').'.php');
		$response->set('content', ob_get_clean());
		
		// include and capture the response
		ob_start();
		include($config->get('templates_dir').'/'.$response->get('layout', 'index').'.php');
		$content = ob_get_clean();
		
		// output all HTTP response headers
		$headers = $response->getHttpHeaders();
		foreach ($headers as $header => $value) {
			header(sprintf('%s: %s', $header, $value)); // TODO: is ":" correct?
		}
		
		echo $content;
	}
}

?>
