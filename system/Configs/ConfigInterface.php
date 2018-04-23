<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.04.2018
 * Time: 19:15
 */

namespace Configs;

use System\Kernel\TypesApp\AbstractApplication;

interface ConfigInterface
{
	/**
	 * @param string $config
	 * @param string $param
	 * @param string $default
	 * @return mixed
	 */
	public static function get(string $config, string $param = '', string $default = '');

	/**
	 * @return array
	 */
	public static function getExceptionHandlers(): array;

	/**
	 * @return array
	 */
	public static function getRouters(): array;

	/**
	 * @param AbstractApplication $application
	 */
	public static function setEnvForConfig(AbstractApplication $application): void;
}