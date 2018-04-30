<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2018
 * Time: 13:34
 */

namespace Http\Middleware;

use Configs\Config;
use Helper\CSRFTokenManager;
use Http\Request\ServerRequest;

class MiddlewareCSRFToken
{
	/**
	 * @param ServerRequest $request
	 * @param RequestHandler $handler
	 * @return \Http\Response\Response
	 */
	public function process(ServerRequest $request, RequestHandler $handler)
	{
		CSRFTokenManager::create()->makeToken();
		return $handler->handle($request, $handler);
	}
}