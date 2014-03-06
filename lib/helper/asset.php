<?php

/**
 * Generate <script type="text/javascript"> tags for registered scripts.
 *
 * @return string
 */
function get_scripts() {
	$result = array();
	$scripts = Response::getInstance()->get('scripts', array(), 'head');
	foreach ($scripts as $src) {
		$result[] = '<script type="text/javascript" src="'.$src.'"></script>';
	}
	
	return implode("\n", $result);
}

/**
 * Output <script type="text/javascript"> tags for registered scripts.
 *
 * @see get_scripts
 */
function include_scripts() {
	echo get_scripts();
}

/**
 * Add a <script type="text/javascript"> tag to the response.
 *
 * @param string src
 */
function add_script($src) {
	$scripts = Response::getInstance()->get('scripts', array(), 'head');
	$scripts[] = $src;
	
	Response::getInstance()->set('scripts', $scripts, 'head');
}

/**
 * Generate <link rel="stylesheet" type="text/css"> tags.
 *
 * @return string
 */
function get_stylesheets() {
	$result = array();
	$stylesheets = Response::getInstance()->get('stylesheets', array(), 'head');
	foreach ($stylesheets as $href) {
		$result[] = '<link rel="stylesheet" type="text/css" href="'.$href.'" />';
	}
	
	return implode("\n", $result);
}

/**
 * Output <link rel="stylesheet" type="text/css"> tags.
 *
 */
function include_stylesheets() {
	echo get_stylesheets();
}

/**
 * Add a <link rel="stylesheet" type="text/css"> tag to the response.
 *
 * @param string href
 */
function add_stylesheet($href) {
	$stylesheets = Response::getInstance()->get('stylesheets', array(), 'head');
	$stylesheets[] = $href;
	
	Response::getInstance()->set('stylesheets', $stylesheets, 'head');
}

?>
