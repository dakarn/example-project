<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:57
 */

namespace Middleware;

use Http\Request\RequestInterface;
use Http\Response\Response;

class RequestHandler implements RequestHandlerInterface
{
	/**
	 * @var Response
	 */
	private $response;

	/**
	 * RequestHandler constructor.
	 */
	public function __construct()
	{
		$this->response = new Response();
	}

	/**
	 * @return Response
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}

	/**
	 * @param RequestInterface $request
	 * @param RequestHandler $handler
	 * @return Response
	 */
	public function handle(RequestInterface $request, RequestHandler $handler): Response
	{
		$curr = StorageMiddleware::currPosition();

		if ($curr >= StorageMiddleware::count()) {
			return $this->response;
		}

		$classMiddleware = StorageMiddleware::get()[$curr]['class'];
		StorageMiddleware::nextPosition();

		/** @var MiddlewareInterface $middleware */
		$middleware = new $classMiddleware();
		return $middleware->process($request, $handler);
	}
}