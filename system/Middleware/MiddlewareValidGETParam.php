<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:29
 */

namespace Middleware;

use Helper\Request;

class MiddlewareValidGETParam implements MiddlewareInterface
{
	public function process(Request $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler, true);
	}
}