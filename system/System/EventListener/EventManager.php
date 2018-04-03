<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.03.2018
 * Time: 1:04
 */

namespace System\EventListener;

class EventManager
{
	private $events = [];

	public function addEventListener(string $event, $className): self
	{
		$this->events[$event] = $className;
		return $this;
	}

	public function hasEventListener(string $event): bool
	{
		return isset($this->events[$event]) ? true : false;
	}

	public function replaceEventListener(string $event, EventListenerInterface $newClass)
	{
		if (isset($this->events[$event])) {
			$this->events[$event] = $newClass;
		}
	}

	public function removeEventListener(string $event)
	{
		unset($this->events[$event]);
	}

	public function runEvent(string $event, array $arguments = [])
	{
		/** @var $classInstance EventListenerInterface */
		if ($this->hasEventListener($event)) {
			$className     = $this->events[$event];
			$classInstance = new $className($arguments);
			$classInstance->execute();
		}
	}
}