<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:57
 */

namespace Middleware;

use Http\RequestInterface;
use System\Response\Response;

class RequestHandler implements RequestHandlerInterface
{
	private $response;

	public function handle(RequestInterface $request, RequestHandler $handler, $isSuccess = true): Response
	{
		if (!$isSuccess) {
			return new Response();
		}

		$this->response = new Response();

		$curr = StorageMiddleware::currPosition();

		if ($curr >= StorageMiddleware::count()) {
			return new Response();
		}

		$classMiddleware = StorageMiddleware::get()[$curr]['class'];
		StorageMiddleware::nextPosition();

		/** @var MiddlewareInterface $middleware */
		$middleware = new $classMiddleware();
		return $middleware->process($request, $handler);
	}
}