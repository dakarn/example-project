<?php

use MiddlewareApp\MiddlewareCheckAuth;

return [
	[
		'path'       => 'search-word',
		'controller' => 'Controller:IndexController',
		'action'     => 'searchWord',
		'allow'      => ['POST'],
	],
	[
		'path'       => 'addIndex',
		'controller' => 'Controller:ElasticController',
		'action'     => 'addIndex',
		'middleware' => MiddlewareCheckAuth::class,
		'allow'      => ['GET', 'POST'],
		'enterData'  => []
	],
	[
		'path'       => 'removeIndex',
		'controller' => 'Controller:ElasticController',
		'action'     => 'removeIndex',
		'allow'      => ['GET'],
	],
	[
		'path'       => 'indexer/{id}/{red}',
		'controller' => 'Controller:ElasticController',
		'action'     => 'indexer',
		'allow'      => ['GET'],
		'regex'      => true,
		'param'      => [
			'id'  => '\d+',
			'red' => '\d+',
		],
	],
	[
		'path'       => 'enterCommand',
		'controller' => 'Controller:ElasticController',
		'action'     => 'enterCommand',
		'allow'      => ['GET'],
	],
	[
		'path'       => 'random-word/{id}',
		'controller' => 'Controller:IndexController',
		'action'     => 'dictionary',
		'allow'      => ['GET', 'POST', 'PUT'],
		'regex'      => true,
		'middleware' => MiddlewareCheckAuth::class,
		'param'      => [
			'id' => '\d{1,5}',
		],
	],
	[
		'default'    => '',
		'controller' => 'Controller:IndexController',
		'action'     => 'index',
		'allow'      => ['GET'],
	],
];