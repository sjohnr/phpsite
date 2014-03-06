<?php

/**
 * Display an error message.
 *
 * @param string name
 * @param string tag
 * @param string css
 * @return string
 */
function form_error($name, $css = 'error', $tag = 'p') {
	$response = Response::getInstance();
	$errors = $response->get('errors');
	
	if (isset($errors[$name])) {
		$html = '<'.$tag.' class="'.$css.'"'.'>';
		$html .= $errors[$name];
		$html .= '</'.$tag.'>';
		
		return $html;
	}
	
	return '';
}

/**
 * Display an error message with no formatting.
 *
 * @param string name
 * @return string
 */
function get_error($name) {
	$response = Response::getInstance();
	$errors = $response->get('errors');
	
	return isset($errors[$name]) ? $errors[$name] : '';
}

/**
 * Determine if a particular error message exists.
 *
 * @param name
 * @return boolean
 */
function has_error($name) {
	$response = Response::getInstance();
	$errors = $response->get('errors');
	
	return isset($errors[$name]);
}

/**
 * Determine if there are error messages.
 *
 * @return boolean
 */
function has_errors() {
	$response = Response::getInstance();
	
	return ($response->has('errors') && count($response->get('errors')) > 0);
}

/**
 * Retrieve the error names for the page.
 *
 * @return array
 */
function error_names() {
	$response = Response::getInstance();
	$errors = $response->get('errors');
	
	return array_keys($errors);
}

?>
