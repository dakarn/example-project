<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.03.2018
 * Time: 0:00
 */

namespace Middleware;

use Http\Request\Request;

interface MiddlewareInterface
{
	/**
	 * @param Request $request
	 * @param RequestHandler $handler
	 * @return mixed
	 */
	public function process(Request $request, RequestHandler $handler);
}