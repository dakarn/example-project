<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.03.2018
 * Time: 1:55
 */

namespace Helper;

use Exception\ResponseException;
use System\Response\ResponseInterface;

class Response
{
	private $response;

	private $data;

	public function __construct($data = '', ResponseInterface $response = null)
	{
		if (!empty($data)) {
			$this->data     = $data;
			$this->response = $response;
		}
	}

	public function write(): ResponseInterface
	{
		if ($this->response instanceof ResponseInterface) {
			$response = $this->response;
			$result   = new $response($this->data);
			echo $result;
			return $result;
		}

		throw ResponseException::notImplementedResponse();
	}

	public function redirect(string $url)
	{
		header('Location: ' . $url);
		exit;
	}

	public function redirectToRoute(string $route, array $arguments = [], int $status = 302)
	{
		header('Location: ' . $route);
		exit;
	}
}