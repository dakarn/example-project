<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:49
 */

namespace Queue;

use Queue\Strategy\RabbitReceiverStrategy;
use Configs\Config;
use Traits\SingletonTrait;
use Queue\Senders\RabbitQueueSender;

class QueueManager
{
	use SingletonTrait;

	/**
	 * @var RabbitReceiverStrategy
	 */
	private $strategy;

	/**
	 * @var array
	 */
	private $handlersTask = [];

	/**
	 * @return RabbitReceiverStrategy
	 */
	public function getReceiverStrategy(): RabbitReceiverStrategy
	{
		if (!$this->strategy instanceof RabbitReceiverStrategy) {
			$this->strategy = new RabbitReceiverStrategy(Config::get('rabbit'));
		}

		return $this->strategy;
	}

	/**
	 * @param string $name
	 * @param AbstractQueueHandler $queueClass
	 * @return QueueManager
	 */
	public function setQueueHandler(string $name, AbstractQueueHandler $queueClass): self
	{
		$this->handlersTask[$name] = $queueClass;
		return $this;
	}

	/**
	 * @param array $queueClasses
	 * @return QueueManager
	 */
	public function setQueueHandlers(array $queueClasses): self
	{
		foreach ($queueClasses as $name => $queueClass) {
			$this->handlersTask[$name] = $queueClass;
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function runHandlers(): bool
	{
		return true;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function runHandler(string $name): bool
	{
		if (empty($this->handlersTask[$name])) {
			throw new \LogicException('Handlers for queue was not setup or do not added!');
		}

		$this->handlersTask[$name]->prepareObject()->loopObserver();
		return true;
	}

	/**
	 * @param Queue $queue
	 * @return RabbitQueueSender
	 */
	public function sender(Queue $queue): RabbitQueueSender
	{
		return (new RabbitQueueSender(Config::get('rabbit')))
			->setParams($queue)
			->build();
	}
}