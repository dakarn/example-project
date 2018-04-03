<?php

namespace System\Router;

use Helper\Request;
use System\AppObjectMemento;
use System\Kernel\GETParam;
use System\Config;

class Routing
{
	private static $isFound = false;

	public static function findRoute($routers, $path): Router
	{
		$path = self::cutSlash($path);

		foreach ($routers as $key => $value) {

			$router = new Router($value);

			if ($router->isRegex()) {
				self::findRouterByRegex($router, $path);
			} else if ($router->getPath() === $path) {
				self::$isFound = true;
			}

			if (self::$isFound) {
				if (!self::isAllowMethod($router->getAllow())) {
					return new Router([]);
				}
				return $router;
			}
		}

		if (!self::$isFound) {
			return new Router(end($routers));
		}

		return new Router([]);
	}

	public static function fillRouterList()
	{
		$routers    = Config::get('router_maps');
		$routerList = new RouterList();

		foreach ($routers as $key => $value) {
			$router = new Router($value);
			$routerList->add($router->getController() . $router->getAction(), $router);
		}

		AppObjectMemento::set(AppObjectMemento::ROUTERS, $routerList);
	}

	public static function getRouterList()
	{
		if (!AppObjectMemento::has(AppObjectMemento::ROUTERS)) {
			self::fillRouterList();
		}

		return AppObjectMemento::get(AppObjectMemento::ROUTERS);
	}

	private static function cutSlash(string $path): string
	{
		if (substr($path, -1) == '/') {
			$path = substr($path, 0 , -1);
		}

		return $path;
	}

	private static function findRouterByRegex(Router $router, string $path)
	{
		$regexPath  = $router->getPath();
		$nameParams = [];

		foreach ($router->getParam() as $key => $p) {
			$regexPath    = str_replace('{' . $key . '}', '('.$p.')', $regexPath);
			$nameParams[] = $key;
		}

		if (preg_match('|^' . $regexPath . '$|', $path, $result)) {
			GETParam::setParamForController($nameParams, $result);
			self::$isFound = true;
		}
	}

	private static function isAllowMethod(array $allow): bool
	{
		if (!in_array(Request::create()->getMethod(), $allow)) {
			return false;
		}

		return true;
	}
}