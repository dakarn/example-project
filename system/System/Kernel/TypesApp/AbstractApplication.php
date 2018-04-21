<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 14:53
 */

namespace System\Kernel\TypesApp;

use App\AppKernel;
use System\EventListener\EventManager;
use Http\Response\Response;
use System\Database\DB;
use Configs\Config;
use System\Database\DatabaseConfigure;
use System\Registry;

abstract class AbstractApplication
{
	/**
	 * @var array
	 */
	const ENV_TYPE = [
		'DEV'  => 'DEV',
		'TEST' => 'TEST',
		'PROD' => 'PROD',
	];

	/**
	 * @var array
	 */
	const APP_TYPE = [
		'Web'     => 'Web',
		'Console' => 'Console',
		'Queue'   => 'Queue',
		'Api'     => 'Api',
	];

	/**
	 * @var string
	 */
	const PREFIX_ACTION = 'Action';

    /**
     * @var string
     */
	protected $env;

    /**
     * @var bool
     */
	protected $wasRun = false;

	/**
	 * @var EventManager
	 */
	protected $eventManager;

	/**
	 * @var AppKernel
	 */
	protected $appKernel;

    /**
     * @var string
     */
	protected $applicationType = '';

    /**
     * AbstractApplication constructor.
     */
	public function __construct()
	{
		Registry::set(Registry::APP, $this);
	}

	public function outputResponse(): void
	{
	}

    /**
     * @param $env
     * @return AbstractApplication
     */
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

    /**
     * @param string $applicationType
     * @return AbstractApplication
     */
	public function setApplicationType(string $applicationType): self
	{
		$this->applicationType = $applicationType;
		return $this;
	}

    /**
     * @return bool
     */
	public function isWebApp(): bool
	{
		return $this->getApplicationType() === self::APP_TYPE['Web'];
	}

    /**
     * @return bool
     */
	public function isConsoleApp(): bool
	{
		return $this->getApplicationType() === self::APP_TYPE['Console'];
	}

    /**
     * @return string
     */
	public function getApplicationType(): string
	{
		return $this->applicationType;
	}

    /**
     * @param EventManager $eventManager
     * @return AbstractApplication
     */
	public function setAppEvent(EventManager $eventManager): self
	{
		$this->eventManager = $eventManager;
		return $this;
	}

    /**
     * @return EventManager
     */
	public function getEventApp(): EventManager
	{
		return $this->eventManager;
	}

    /**
     * @param AppKernel $appKernel
     * @return AbstractApplication
     */
	public function setAppKernel(AppKernel $appKernel): self
	{
		$this->appKernel = $appKernel;
		return $this;
	}

    /**
     * @var void
     */
	public function isRepeatRun()
	{
		if ($this->wasRun) {
			throw new \LogicException('Application was run already. A repeat run is impossible!');
		}

		$this->wasRun = true;
	}

    /**
     * @return string
     */
	public function getEnvironment(): string
	{
		return $this->env;
	}

    /**
     * @param \Throwable $e
     * @throws \Throwable
     */
	public function outputException(\Throwable $e)
	{
		if ($this->env == self::ENV_TYPE['DEV'] || $this->env == self::ENV_TYPE['TEST']) {
			throw $e;
		} else {
			if($this->isWebApp()) {
				(new Response())->redirect(URL);
			}
		}
	}

    /**
     * @var void
     */
	protected function runInternal(): void
    {
        Config::setEnvForConfig($this);
        DB::setConfigure(new DatabaseConfigure(Config::get('common', 'mysql')));
    }

	abstract public function run();
}