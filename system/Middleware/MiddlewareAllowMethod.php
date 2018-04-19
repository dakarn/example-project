<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:28
 */

namespace Middleware;

use Exception\ControllerException;
use Http\Request\RequestInterface;
use Http\Response\Response;
use System\Router\Routing;

class MiddlewareAllowMethod implements MiddlewareInterface
{
	/**
	 * @param RequestInterface $request
	 * @param RequestHandler $handler
	 * @return Response
	 * @throws ControllerException
	 */
	public function process(RequestInterface $request, RequestHandler $handler): Response
	{
		$router = Routing::getFoundRouter();

		if (!in_array($request->getMethod(), $router->getAllow())) {
			throw ControllerException::deniedMethod($request->getMethod());
		}

		if (!empty($router->getMiddleware())) {
			foreach ($router->getMiddleware() as $middleware) {
				StorageMiddleware::addOne(['class' => $middleware]);
			}
		}

		return $handler->handle($request, $handler);
	}
}