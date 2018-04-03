<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.03.2018
 * Time: 17:27
 */

namespace System\Service;

use Exception\ServiceException;

class ServiceIncluding
{
	private static $service;

	private static $services = [];

	public static function loadServiceFile()
	{
		self::$services = include_once(PATH_APP . 'Config/service.php');
	}

	public static function setService(string $service)
	{
		self::$service = $service;
	}

	public static function runService()
	{
		/** @var $service ServiceInterface */
		if (isset($services[self::$service])) {
			$service = new self::$services[self::$service]['class']();
			$service->setArguments(self::$services[self::$service]['arguments']);

			return $service;
		}

		throw new ServiceException('This service not found');

	}
}