<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:43
 */

namespace App\MiddlewareApp;

use Http\Request\Request;
use Middleware\MiddlewareInterface;
use Middleware\RequestHandler;

class MiddlewareCheckAuth implements MiddlewareInterface
{
	/**
	 * @param Request $request
	 * @param RequestHandler $handler
	 * @return \Http\Response\Response
	 */
	public function process(Request $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler);
	}
}