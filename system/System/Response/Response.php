<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.03.2018
 * Time: 1:55
 */

namespace System\Response;

use Exception\ResponseException;
use Exception\RoutingException;
use System\Router\Routing;

class Response
{
	private $responseType;

	private $response;

	private $data;

	private $param;

	public function __construct($data = null, string $responseType = '', array $param = [])
	{
		$this->param        = $param;
		$this->data         = $data;
		$this->responseType = $responseType;

		if ($data !== null) {
			$this->render();
		}
	}

	public function render(): void
	{
		switch ($this->responseType) {
			case 'simple':
				$this->response = new SimpleResponse();
				break;
			case 'json':
				$this->response = new JsonResponse();
				break;
			case 'api':
				$this->response = new ApiResponse();
				break;
			case 'xml':
				$this->response = new XMLResponse();
				break;
			default:
				throw ResponseException::invalidResponse();
				break;
		}

		echo $this->response->render($this->data, $this->param);
	}

	public function redirect(string $url): void
	{
		header('Location: ' . $url);
		exit;
	}

	public function redirectToRoute(string $routerName, array $arguments, int $status): void
	{
		$router = Routing::getRouterList()->get($routerName);

		if (!empty($router)) {
			$url = URL . Routing::replaceRegexToParam($router->getPath(), $router->getParam(), $arguments);
			header('Location: ' . $url, true, $status);
			exit;
		}

		throw RoutingException::notFound([$routerName]);

	}
}