<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 14:53
 */

namespace System\Kernel\TypesApp;

use System\EventListener\EventManager;
use Http\Response\Response;
use System\Router\Router;

abstract class AbstractApplication
{
	const ENV_TYPE = [
		'DEV'  => 'DEV',
		'TEST' => 'TEST',
		'PROD' => 'PROD',
	];

	const PREFIX_ACTION = 'Action';

	protected $env;

	protected $wasRun = false;

	/**
	 * @var EventManager
	 */
	protected $eventManager;

	/**
	 * @var \AppKernel
	 */
	protected $appKernel;

	public function setEnvironment($env): self
	{
		if (!isset(self::ENV_TYPE[$env])) {
			throw new \InvalidArgumentException('Try setup invalid environment!');
		}

		if (!empty($this->env)) {
			throw new \LogicException('Environment setup already!');
		}

		$this->env = $env;
		return $this;
	}

	public function setAppEvent(EventManager $eventManager): self
	{
		$this->eventManager = $eventManager;
		return $this;
	}

	public function getEventApp(): EventManager
	{
		return $this->eventManager;
	}

	public function setAppKernel(\AppKernel $appKernel): self
	{
		$this->appKernel = $appKernel;
		return $this;
	}

	public function isRepeatRun()
	{
		if ($this->wasRun) {
			throw new \LogicException('Application was run already. A repeat run is impossible!');
		}

		$this->wasRun = true;
	}

	public function getEnv(): string
	{
		return $this->env;
	}

	public function outputException(\Throwable $e)
	{
		if ($this->env == self::ENV_TYPE['DEV'] || $this->env == self::ENV_TYPE['TEST']) {
			throw $e;
		} else {
			(new Response())->redirect(URL);
		}
	}

	abstract public function run(Router $router = null);
}