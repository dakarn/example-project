<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:28
 */

namespace Middleware;

use Helper\Request;

class MiddlewareAllowMethod implements MiddlewareInterface
{
	public function process(Request $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler, true);
	}
}