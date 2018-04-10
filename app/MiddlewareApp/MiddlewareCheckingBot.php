<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.03.2018
 * Time: 2:13
 */

namespace MiddlewareApp;

use Helper\Request;
use Http\RequestInterface;
use Middleware\RequestHandler;
use Middleware\MiddlewareInterface;

class MiddlewareCheckingBot implements MiddlewareInterface
{
	public function process(RequestInterface $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler);
	}
}