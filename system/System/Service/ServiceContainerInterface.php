<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.03.2018
 * Time: 22:48
 */

namespace System\Service;

interface ServiceContainerInterface
{
	public function add(string $service): ServiceContainerInterface;

	public function get(string $service): ServiceInterface;

	public function setServiceConfig(array $serviceConfig): ServiceContainerInterface;
}