<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:28
 */

namespace Middleware;

use Http\RequestInterface;
use System\Response\Response;

class MiddlewareAllowMethod implements MiddlewareInterface
{
	public function process(RequestInterface $request, RequestHandler $handler): Response
	{
		$handler->getResponse()->withCookie('dfdf', 'sdsd');

		return $handler->handle($request, $handler, true);
	}
}