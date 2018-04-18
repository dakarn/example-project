<?php

namespace App;

use System\EventListener\EventManager;
use System\EventListener\EventTypes;
use App\Event\BeforeControllerEvent;
use System\AppObjectMemento;

final class AppEvent
{
	public function installEvents(EventManager $eventManager): EventManager
	{
		$eventManager->addEventListener(EventTypes::BEFORE_CONTROLLER, BeforeControllerEvent::class);

		AppObjectMemento::set(AppObjectMemento::APP_EVENT, $eventManager);
		return $eventManager;
	}
}