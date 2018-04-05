<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.03.2018
 * Time: 20:33
 */

namespace System\Controller;

use System\EventListener\EventManager;
use System\Router\RouteData;

interface ControllerInterface
{
	public function __before(RouteData $route);

	public function __after(RouteData $route);

	public function __construct(EventManager $eventManager);

	public function __destruct();
}