<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 14:53
 */

namespace System\TypesApp;

use Exception\MiddlewareException;
use Middleware\RequestHandler;
use Middleware\StorageMiddleware;
use Providers\StorageProviders;
use System\Config;
use System\EventListener\EventTypes;
use Helper\Request;
use System\Logger\LoggerStorage;
use System\Logger\LogLevel;
use System\Logger\Logger;
use System\Logger\LoggerAware;
use System\Render;
use System\Response\ResponseInterface;
use System\Router\RouteData;
use System\Controller\AbstractController;
use System\Router\Router;
use System\Kernel\GETParam;

class WebApp extends AbstractApplication
{
	public function setAppKernel(\AppKernel $appKernel): AbstractApplication
	{
		parent::setAppKernel($appKernel);
		StorageMiddleware::add($appKernel->getMiddlewares());
		StorageProviders::add($appKernel->getProviders());

		return $this;
	}

	public function run(Router $router = null)
	{
		$this->isRepeatRun();

		if (!$router->isFilled()) {
			throw new \InvalidArgumentException('Parameters for run Application is empty!');
		}

		if (!$this->runMiddleware($router)) {
			throw MiddlewareException::failedResult();
		}

		$this->runController($router);
	}

	public function runController(Router $router)
	{
		/** @var AbstractController $controller */

		$className = '\\' . str_replace(':', '\\', $router->getController());
		$action    = $router->getAction() . self::PREFIX_ACTION;

		try {
			$controller = new $className();
			$routeData  = $this->setRouteData($action, $router);

			$this->eventManager->runEvent(EventTypes::BEFORE_CONTROLLER, [
				'controller' => $routeData->getControllerName(),
				'action'     => $routeData->getActionName(),
			]);

			if (!$controller->__before($routeData)) {
				$this->defaultTemplate(null);
				return;
			}

			$this->eventManager->runEvent(EventTypes::BEFORE_ACTION);
			$result = call_user_func_array([$controller, $action], array_values(GETParam::getParamForController()));
			$this->eventManager->runEvent(EventTypes::AFTER_ACTION);

			$this->defaultTemplate($result);

			$controller->__after($routeData);
			$this->eventManager->runEvent(EventTypes::AFTER_CONTROLLER);
		} catch(\Throwable $e) {

			LoggerAware::setlogger(new Logger())->log(LogLevel::ERROR, $e->getMessage());
			LoggerStorage::create()->releaseLog();
			$this->eventManager->runEvent(EventTypes::THROW_EXCEPTION, ['exception' => $e]);

			$this->outputException($e);
		}
	}

	public function runMiddleware(Router $router): bool
	{
		if (!empty($router->getMiddleware())) {
			StorageMiddleware::addOne(['class' => $router->getMiddleware()]);
		}

		if (!isset(StorageMiddleware::get()[0])) {
			return true;
		}

		/** @var RequestHandler $runHandler */
		$runHandler = new RequestHandler();
		return $runHandler->handle(Request::create(), $runHandler);

	}

	private function defaultTemplate($result)
	{
		if (!$result instanceof Render &&
			!$result instanceof ResponseInterface) {
			new Render(Config::get('common', 'defaultTemplate'));
			$this->eventManager->runEvent(EventTypes::DEFAULT_TEMPLATE);
		}
	}

	private function setRouteData(string $action, Router $router): RouteData
	{
		$routeData  = (new RouteData())
			->setData('action', $action)
			->setData('controller', substr($router->getController(), strrpos($router->getController(), ':') + 1));

		return $routeData;
	}
}