<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:43
 */

namespace MiddlewareApp;

use Helper\Request;
use Middleware\MiddlewareInterface;
use Middleware\RequestHandler;

class MiddlewareCheckAuth implements MiddlewareInterface
{
	public function process(Request $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler);
	}
}