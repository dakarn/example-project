<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.04.2018
 * Time: 22:33
 */

namespace Middleware;

use Http\Request\RequestInterface;
use System\Controller\LauncherController;
use System\Registry;
use System\Render;
use System\Router\Routing;

class MiddlewareController implements MiddlewareInterface
{
	/**
	 * @param RequestInterface $request
	 * @param RequestHandler $handler
	 * @return \Http\Response\Response
	 */
	public function process(RequestInterface $request, RequestHandler $handler)
	{
        $launcher = new LauncherController(
            Registry::get(Registry::APP),
            Routing::getFoundRouter(),
            $request,
            $handler->getResponse()
        );

        $result = $launcher->execute();

        if ($result instanceof Render) {
            $handler->getResponse()->withBody($result->render());
        } else {
            $handler->getResponse()->withBody($result->getBody());
        }

        return $handler->handle($request, $handler);
	}
}