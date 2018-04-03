<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 23:59
 */

namespace Middleware;

use Helper\Request;

interface RequestHandlerInterface
{
	public function handle(Request $request, RequestHandler $handler, $isSuccess = true);
}