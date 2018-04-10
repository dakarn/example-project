<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.03.2018
 * Time: 0:00
 */

namespace Middleware;

use Http\RequestInterface;

interface MiddlewareInterface
{
	public function process(RequestInterface $request, RequestHandler $handler);
}