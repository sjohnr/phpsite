<?php

return array(
	'filters' => array(
		//'config' => array( // now statically loaded in Controller#dispatch()
		//	'className' => 'ConfigFilter',
		//),
		'session' => array(
			'className' => 'SessionFilter',
		),
		'request' => array(
			'className' => 'RequestFilter',
		),
		'response' => array(
			'className' => 'ResponseFilter',
		),
		'authentication' => array(
			'className' => 'AuthenticationFilter',
		),
		'execution' => array(
			'className' => 'ExecutionFilter',
		),
	),
	'routes' => array(
		'default' => array(
			'route' => '/:module/:action/*',
			'defaults' => array(
				
			),
		),
		'index' => array(
			'route' => '/:module',
			'defaults' => array(
				'action' => 'index',
			),
		),
		'homepage' => array(
			'route' => '/',
			'defaults' => array(
				'module' => 'default',
				'action' => 'index',
			),
		),
	),
	'response' => array(
		'title' => '',
		'keywords' => '',
		'description' => '',
		'layout' => 'index',
	),
);

?>
