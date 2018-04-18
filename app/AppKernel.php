<?php

namespace App;

use Middleware\MiddlewareAllowMethod;
use Middleware\MiddlewareController;
use Middleware\MiddlewareRouting;
use Middleware\MiddlewareValidGETParam;
use System\AppObjectMemento;

final class AppKernel
{
	private $middlewares = [];

	private $providers = [];

	public function __construct()
	{
		AppObjectMemento::set(AppObjectMemento::APP_KERNEL, $this);
	}

	public function installMiddlewares(): self
	{
		$this->middlewares[] = [
			'autoStart' => true,
			'class'     => MiddlewareRouting::class,
		];

		$this->middlewares[] = [
			'autoStart' => true,
			'class'     => MiddlewareAllowMethod::class,
		];

		$this->middlewares[] = [
			'autoStart' => false,
			'class'     => MiddlewareApp\MiddlewareCheckingBot::class,
		];

		$this->middlewares[] = [
			'autoStart' => false,
			'class'     => MiddlewareApp\MiddlewareCheckAuth::class,
		];

		$this->middlewares[] = [
			'autoStart' => false,
			'class'     => MiddlewareApp\MiddlewareCheckAjax::class,
		];

		$this->middlewares[] = [
			'autoStart' => true,
			'class'     => MiddlewareController::class,
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