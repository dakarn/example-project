<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.03.2018
 * Time: 14:55
 */

namespace System\Controller;

use Exception\ControllerException;
use Helper\Cookie;
use Http\Request\ServerRequest;
use Http\Response\Response;
use Helper\Session;
use System\Registry;
use Configs\Config;
use System\EventListener\EventManager;
use System\Logger\Logger;
use System\Logger\LoggerAware;
use System\Router\Routing;
use System\Service\ServiceContainer;
use System\Service\ServiceInterface;
use System\Render;
use System\Router\RouteData;

abstract class AbstractController implements ControllerInterface
{
	/**
	 * @var EventManager
	 */
	protected $eventManager;

	/**
	 * @var Response
	 */
	protected $response;

    /**
     * @var ServerRequest
     */

	protected $request;
    /**
     * AbstractController constructor.
     * @param EventManager $eventManager
     * @param Response $response
     * @param ServerRequest $request
     */
	public function __construct(EventManager $eventManager, Response $response, ServerRequest $request)
	{
	    $this->request      = $request;
		$this->eventManager = $eventManager;
		$this->response     = $response;
	}

	/**
	 * @param string $url
	 */
	protected function redirect(string $url)
	{
		$this->response->redirect($url);
	}

	/**
	 * @return Render
	 */
	protected function notFound(): Render
	{
		return new Render(Config::get('common', 'errors')['404']);
	}

	/**
	 * @param string $level
	 * @param string $message
	 */
	protected function log(string $level, string  $message)
	{
		LoggerAware::setlogger(new Logger())->log($level, $message);
	}

	/**
	 * @param RouteData $route
	 * @return bool
	 */
	public function __before(RouteData $route): bool
	{
		return true;
	}

	/**
	 * @param RouteData $route
	 * @return bool
	 */
	public function __after(RouteData $route): bool
	{
		return false;
	}

	/**
	 * @return ServerRequest
	 */
	protected function request(): ServerRequest
	{
		return $this->request;
	}

	/**
	 * @param $data
	 * @param string $responseType
	 * @param array $param
	 * @return Response
	 */
	protected function response($data, string $responseType = '', array $param = []): Response
	{
		return $this->response->withBody($data, $responseType, $param);
	}

	/**
	 * @return Session
	 */
	protected function session(): Session
	{
		return $this->request()->getSession();
	}

	/**
	 * @return Cookie
	 */
	protected function cookie(): Cookie
	{
		return $this->request()->getCookie();
	}

	/**
	 * @param string $invokeRouter
	 * @return mixed
	 * @throws ControllerException
	 */
	protected function invokeRouter(string $invokeRouter)
	{
		$routerList = Routing::getRouterList();
		$router     = $routerList->get($invokeRouter);

		if (!empty($router)) {
			$app = Registry::get(Registry::APP);
			return $app->runController($router);
		}

		throw ControllerException::notFoundController([$invokeRouter]);
	}

	/**
	 * @param string $nameService
	 * @return ServiceInterface
	 */
	protected function get(string $nameService): ServiceInterface
	{
		return ServiceContainer::create()
			->setServiceConfig(Config::get('service'))
			->add($nameService)
			->get($nameService);
	}

	/**
	 * @param string $template
	 * @param array $param
	 * @return Render
	 */
	protected function render(string $template, array $param = []): Render
	{
		return new Render($template, $param);
	}

	/**
	 * @param string $routeName
	 * @param array $arguments
	 * @param int $status
	 */
	protected function redirectToRoute(string $routeName, array $arguments = [], int $status = 302): void
	{
		$this->response->redirectToRoute($routeName, $arguments, $status);
	}

	/**
	 *
	 */
	public function __destruct()
	{

	}
}