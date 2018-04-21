<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.04.2018
 * Time: 15:10
 */

namespace App\MiddlewareApp;

use Http\Request\ServerRequest;
use Middleware\MiddlewareInterface;
use Middleware\RequestHandler;

class MiddlewareCheckAjax implements MiddlewareInterface
{
    /**
     * @param ServerRequest $request
     * @param RequestHandler $handler
     * @return \Http\Response\Response
     */
	public function process(ServerRequest $request, RequestHandler $handler)
	{
		if (!$request->isAjax()) {

		}

		return $handler->handle($request, $handler);
	}
}