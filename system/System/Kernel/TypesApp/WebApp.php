<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 14:53
 */

namespace System\Kernel\TypesApp;

use App\AppKernel;
use Exception\ResponseException;
use Middleware\RequestHandler;
use Middleware\StorageMiddleware;
use Providers\StorageProviders;
use System\Config;
use System\EventListener\EventTypes;
use Http\Request\Request;
use System\Logger\LoggerStorage;
use System\Logger\LogLevel;
use System\Logger\Logger;
use System\Logger\LoggerAware;
use System\Render;
use Http\Response\Response;
use Http\Response\ResponseInterface;
use System\Router\RouteData;
use System\Controller\AbstractController;
use System\Router\Router;
use System\Kernel\GETParam;

final class WebApp extends AbstractApplication
{
	/**
	 * @var Response
	 */
	public $response;

	/**
	 * @var Response | Render
	 */
	private $resultAction;

	public function setAppKernel(AppKernel $appKernel): AbstractApplication
	{
		parent::setAppKernel($appKernel);
		StorageMiddleware::add($appKernel->getMiddlewares());
		StorageProviders::add($appKernel->getProviders());

		return $this;
	}

	public function run(Router $router = null): void
	{
		if (!$router->isFilled()) {
			throw new \InvalidArgumentException('A route with this address is not installed on the system!');
		}

		$this->response = $this->runMiddleware($router);
		$this->runController($router);
		$this->flushResponse($this->resultAction);
	}

	public function runController(Router $router)
	{
		/** @var AbstractController $controller */

		$className = 'App\\' . str_replace(':', '\\', $router->getController());
		$action    = $router->getAction() . self::PREFIX_ACTION;

		try {
			$routeData  = $this->setRouteData($action, $router);

			$this->eventManager->runEvent(EventTypes::BEFORE_CONTROLLER, [
				'controller' => $routeData->getControllerName(),
				'action'     => $routeData->getActionName(),
			]);

			$controller = new $className($this->eventManager, $this->response);

			if (!$controller->__before($routeData)) {
				$this->defaultTemplate(null);
				return;
			}

			$this->eventManager->runEvent(EventTypes::BEFORE_ACTION);
			$this->resultAction = call_user_func_array([$controller, $action], array_values(GETParam::getParamForController()));
			$this->eventManager->runEvent(EventTypes::AFTER_ACTION);

			$this->defaultTemplate($this->resultAction);

			$controller->__after($routeData);
			$this->eventManager->runEvent(EventTypes::AFTER_CONTROLLER);
		} catch(\Throwable $e) {

			LoggerAware::setlogger(new Logger())->log(LogLevel::ERROR, $e->getMessage());
			LoggerStorage::create()->releaseLog();
			$this->eventManager->runEvent(EventTypes::APP_THROW_EXCEPTION, ['exception' => $e]);

			$this->outputException($e);
		}
	}

	public function flushResponse($resultAction): void
	{
		$this->response->sendHeaders();

		if ($resultAction instanceof Render) {
			$this->response->setData($resultAction->render())->render();
		} else {
			$this->response->render();
		}
	}

	public function runMiddleware(Router $router): Response
	{
		if (!empty($router->getMiddleware())) {
			foreach ($router->getMiddleware() as $middleware) {
				StorageMiddleware::addOne(['class' => $middleware]);
			}
		}

		if (!isset(StorageMiddleware::get()[0])) {
			throw ResponseException::invalidResponse();
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