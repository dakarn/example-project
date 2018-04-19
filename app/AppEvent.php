<?php

namespace App;

use System\EventListener\EventManager;
use System\EventListener\EventTypes;
use App\Event\BeforeControllerEvent;
use System\Registry;

final class AppEvent
{
	/**
	 * @param EventManager $eventManager
	 * @return EventManager
	 */
	public function installEvents(EventManager $eventManager): EventManager
	{
		$eventManager->addEventListener(EventTypes::BEFORE_CONTROLLER, BeforeControllerEvent::class);

		Registry::set(Registry::APP_EVENT, $eventManager);
		return $eventManager;
	}
}