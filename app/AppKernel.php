<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.03.2018
 * Time: 22:31
 */

use Middleware\MiddlewareAllowMethod;
use Middleware\MiddlewareValidGETParam;
use System\AppObjectMemento;

class AppKernel
{
	private $middlewares = [];

	private $providers = [];

	public function __construct()
	{
		AppObjectMemento::set(AppObjectMemento::APP_EVENT, $this);
	}

	public function installMiddlewares(): self
	{
		$this->middlewares[] = [
			'autoStart' => true,
			'class'     => MiddlewareAllowMethod::class,
		];

		$this->middlewares[] = [
			'autoStart' => true,
			'class'     => MiddlewareValidGETParam::class,
		];

		$this->middlewares[] = [
			'autoStart' => false,
			'class'     => MiddlewareApp\MiddlewareCheckingBot::class,
		];

		$this->middlewares[] = [
			'autoStart' => false,
			'class'     => MiddlewareApp\MiddlewareCheckAuth::class,
		];

		return $this;
	}

	public function installProviders(): self
	{
		return $this;
	}

	public function getProviders(): array
	{
		return $this->providers;
	}

	public function getMiddlewares(): array
	{
		return $this->middlewares;
	}
}