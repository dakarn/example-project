<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 14:53
 */

namespace System\Kernel\TypesApp;

use App\AppKernel;
use Exception\ControllerException;
use Exception\ResponseException;
use Middleware\RequestHandler;
use Middleware\StorageMiddleware;
use Providers\StorageProviders;
use System\Controller\ControllerInterface;
use System\EventListener\EventTypes;
use Http\Request\Request;
use System\Logger\LoggerStorage;
use System\Logger\LogLevel;
use System\Logger\Logger;
use System\Logger\LoggerAware;
use System\Render;
use Http\Response\Response;
use System\Router\RouteData;
use System\Controller\AbstractController;
use System\Router\Router;
use System\Kernel\GETParam;
use System\Router\Routing;

final class WebApp extends AbstractApplication
{
	/**
	 * @var Response
	 */
	public $response;

	public function setAppKernel(AppKernel $appKernel): AbstractApplication
	{
		parent::setAppKernel($appKernel);
		StorageProviders::add($appKernel->getProviders());

		return $this;
	}

	public function handle(): WebApp
	{
		$request = Request::create();
		$request->handle($this->appKernel, $request);

		return $this;
	}

	public function run(): void
	{

	}

	public function outputResponse(): void
	{

		$this->response = Request::create()->resultHandle();

		$this->response->sendHeaders();
		$this->response->output();
	}
}