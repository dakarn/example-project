<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.03.2018
 * Time: 2:13
 */

namespace App\MiddlewareApp;

use Http\Request\RequestInterface;
use Middleware\RequestHandler;
use Middleware\MiddlewareInterface;

class MiddlewareCheckingBot implements MiddlewareInterface
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