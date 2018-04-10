<?php

use MiddlewareApp\MiddlewareCheckAjax;

return [
	[
		'name'       => 'api-get',
		'path'       => 'api/get',
		'controller' => 'Controller:Api:ApiController',
		'action'     => 'get',
		'allow'      => ['GET'],
		'middleware' => [MiddlewareCheckAjax::class],
	],
	[
		'name'       => 'api-add',
		'path'       => 'api/add',
		'controller' => 'Controller:Api:ApiController',
		'action'     => 'add',
		'allow'      => ['PUT'],
		'middleware' => [MiddlewareCheckAjax::class],
	],
	[
		'name'       => 'api-update',
		'path'       => 'api/update',
		'controller' => 'Controller:Api:ApiController',
		'action'     => 'update',
		'allow'      => ['POST'],
		'middleware' => [MiddlewareCheckAjax::class],
	],
	[
		'name'       => 'api-delete',
		'path'       => 'api/delete',
		'controller' => 'Controller:Api:ApiController',
		'action'     => 'delete',
		'allow'      => ['DELETE'],
		'middleware' => [MiddlewareCheckAjax::class],
	],
	[
		'name'       => 'search-word-api',
		'path'       => 'api/search-word',
		'controller' => 'Controller:Api:ApiController',
		'action'     => 'searchWord',
		'allow'      => ['POST'],
		'middleware' => [MiddlewareCheckAjax::class],
	],
];