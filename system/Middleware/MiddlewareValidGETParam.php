<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:29
 */

namespace Middleware;

use Http\RequestInterface;

class MiddlewareValidGETParam implements MiddlewareInterface
{
	public function process(RequestInterface $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler, true);
	}
}