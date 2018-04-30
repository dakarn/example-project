<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2018
 * Time: 13:58
 */

namespace Http\Middleware;

use Http\Request\ServerRequest;

class MiddlewarePreController
{
	/**
	 * @param ServerRequest $request
	 * @param RequestHandler $handler
	 * @return \Http\Response\Response
	 */
	public function process(ServerRequest $request, RequestHandler $handler)
	{
		return $handler->handle($request, $handler);
	}
}