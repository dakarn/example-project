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
use Helper\File;
use System\Router\Routing;

class Response
{
	private $response;

	private $data;

	public function __construct($data = '', ResponseInterface $response = null)
	{
		if (!empty($data)) {
			$this->response = $response;
		}
	}

	public function renderFile(File $file)
	{

	}

	public function render($data): ResponseInterface
	{
		if ($this->response instanceof ResponseInterface) {
			$this->data     = $data;
			$response = $this->response;
			$result   = new $response($this->data);
			echo $result;
			return $result;
		}

		throw ResponseException::notImplementedResponse();
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