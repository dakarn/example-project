<?php

return [
	[
		'path'       => 'api/get',
		'controller' => 'Controller:Ai:ApiController',
		'action'     => 'get',
		'allow'      => ['GET'],
	],
	[
		'path'       => 'api/add',
		'controller' => 'Controller:Ai:ApiController',
		'action'     => 'add',
		'allow'      => ['PUT'],
	],
	[
		'path'       => 'api/update',
		'controller' => 'Controller:Ai:ApiController',
		'action'     => 'update',
		'allow'      => ['POST'],
	],
	[
		'path'       => 'api/delete',
		'controller' => 'Controller:Ai:ApiController',
		'action'     => 'delete',
		'allow'      => ['DELETE'],
	],
];