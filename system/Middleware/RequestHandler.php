<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:57
 */

namespace Middleware;

use Helper\Request;

class RequestHandler implements RequestHandlerInterface
{
	public function handle(Request $request, RequestHandler $handler, $isSuccess = true)
	{
		if (!$isSuccess) {
			return false;
		}

		$curr = StorageMiddleware::currPosition();

		if ($curr >= StorageMiddleware::count()) {
			return true;
		}

		$classMiddleware = StorageMiddleware::get()[$curr]['class'];
		StorageMiddleware::nextPosition();

		/** @var MiddlewareInterface $middleware */
		$middleware = new $classMiddleware();
		return $middleware->process($request, $handler);
	}
}