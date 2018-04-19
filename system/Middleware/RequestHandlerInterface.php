<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:59
 */

namespace Middleware;

use Http\Request\Request;
use Http\Response\Response;

interface RequestHandlerInterface
{
	/**
	 * @param Request $request
	 * @param RequestHandler $handler
	 * @return Response
	 */
	public function handle(Request $request, RequestHandler $handler): Response;
}