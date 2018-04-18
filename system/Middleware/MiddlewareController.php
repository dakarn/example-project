<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.04.2018
 * Time: 22:33
 */

namespace Middleware;

use Http\Request\RequestInterface;

class MiddlewareController implements MiddlewareInterface
{
	public function process(RequestInterface $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler);
	}
}