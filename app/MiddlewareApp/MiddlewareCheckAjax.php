<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 05.04.2018
 * Time: 15:10
 */

namespace App\MiddlewareApp;

use Http\Request\Request;
use Middleware\RequestHandler;

class MiddlewareCheckAjax
{
	public function process(Request $request, RequestHandler $handler)
	{
		if (!$request->isAjax()) {

		}

		return $handler->handle($request, $handler);
	}
}