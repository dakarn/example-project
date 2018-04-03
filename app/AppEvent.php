
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.03.2018
 * Time: 1:45
 */

use System\EventListener\EventManager;
use System\EventListener\EventTypes;
use Event\BeforeControllerEvent;
use System\AppObjectMemento;

class AppEvent
{
	public function installEvents(EventManager $eventManager): EventManager
	{
		$eventManager->addEventListener(EventTypes::BEFORE_CONTROLLER, BeforeControllerEvent::class);

		AppObjectMemento::set(AppObjectMemento::APP_EVENT, $eventManager);
		return $eventManager;
	}
}