<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.03.2018
 * Time: 1:55
 */

namespace Http\Response;

use Exception\ResponseException;
use Exception\RoutingException;
use Helper\Cookie;
use System\Render;
use System\Router\Routing;

class Response
{
	/**
	 * @var string
	 */
	private $responseType = 'simple';

	/**
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * @var string
	 */
	private $data = '';

	/**
	 * @var array
	 */
	private $param = [];

	/**
	 * @var array
	 */
	private $headers = [];

	/**
	 * @var string
	 */
	private $template = '';

	/**
	 * @var array
	 */
	private $cookies = [];

	/**
	 * @var array
	 */
	private $status = [];

	/**
	 * Response constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * @return Response
	 */
	public function setAccessOrigin(): Response
	{
		return $this;
	}

	public function getBody()
	{
		return $this->data;
	}

	/**
	 * @param $data
	 * @param string $responseType
	 * @param array $param
	 * @return Response
	 */
	public function withBody($data, string $responseType = 'simple', array $param = []): Response
	{
		$this->param        = $param;
		$this->data         = $this->data . $data;
		$this->responseType = $responseType;
		return $this;
	}

	/**
	 * @return Response
	 */
	public function output(): Response
	{
		$this->selectResponse();

		echo $this->response->render($this->data, $this->param);
		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return Response
	 */
	public function withHeader(string $name, string $value): Response
	{
		$this->headers[$name] = $value;
		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return Response
	 */
	public function withCookie(string $name, string $value): Response
	{
		$this->cookies[$name] = $value;
		return $this;
	}

	/**
	 * @param string $template
	 * @return Response
	 */
	public function withTemplate(string $template): Response
	{
		$this->template = $template;
		return $this;
	}

	/**
	 * @param string $code
	 * @param string $text
	 * @return Response
	 */
	public function withStatus(string $code, string $text): Response
	{
		$this->status[$code] = $text;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function sendHeaders(): bool
	{
		foreach ($this->headers as $headerKey => $header) {
			header($headerKey . ': ' . $header, false);
		}

		foreach ($this->cookies as $cookieKey => $cookie) {
			Cookie::create()->set($cookieKey, $cookie);
		}

		return true;
	}

	/**
	 * @param string $url
	 */
	public function redirect(string $url): void
	{
		header('Location: ' . $url);
		exit;
	}

	/**
	 * @param string $routerName
	 * @param array $arguments
	 * @param int $status
	 * @throws RoutingException
	 */
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

	private function selectResponse(): void
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
	}
}