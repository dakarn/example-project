<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:43
 */

namespace App\MiddlewareApp;

use Http\Request\RequestInterface;
use Middleware\MiddlewareInterface;
use Middleware\RequestHandler;

class MiddlewareCheckAuth implements MiddlewareInterface
{
	/**
	 * @param RequestInterface $request
	 * @param RequestHandler $handler
	 * @return \Http\Response\Response
	 */
	public function process(RequestInterface $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler);
	}
}