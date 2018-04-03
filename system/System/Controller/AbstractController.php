<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.03.2018
 * Time: 14:55
 */

namespace System\Controller;

use Exception\ControllerException;
use Helper\Response;
use System\AppObjectMemento;
use System\Config;
use System\Logger\Logger;
use System\Logger\LoggerAware;
use System\Router\Routing;
use System\Service\ServiceContainer;
use System\Service\ServiceInterface;
use System\Render;
use System\Router\RouteData;

abstract class AbstractController implements ControllerInterface
{
	protected function redirect(string $url)
	{
		(new Response())->redirect($url);
	}

	protected function notFound()
	{
		return new Render(Config::get('common', 'errors')['404']);
	}

	protected function log(string $level, string  $message)
	{
		LoggerAware::setlogger(new Logger())->log($level, $message);
	}

	public function __before(RouteData $route)
	{
		return true;
	}

	public function __after(RouteData $route)
	{
		return false;
	}

	protected function getUser()
	{

	}

	protected function invokeController(string $controllerClass, string $action)
	{
		$routerList = Routing::getRouterList();
		$router     = $routerList->get($controllerClass . $action);

		if (!empty($router)) {
			$app = AppObjectMemento::get(AppObjectMemento::APP);
			return $app->runController($router);
		}

		throw ControllerException::notFound([$controllerClass]);
	}

	protected function get(string $nameService): ServiceInterface
	{
		return ServiceContainer::create()
			->setServiceConfig(Config::get('service'))
			->addService($nameService)
			->getService($nameService);
	}

	protected function render(string $template, array $param = []): Render
	{
		return new Render($template, $param);
	}

	protected function redirectToRoute(string $route, array $arguments = [], int $status = 302)
	{
		(new Response())->redirectToRoute($route);
	}
}