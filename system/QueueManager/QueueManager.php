<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:49
 */

namespace QueueManager;

use QueueManager\Senders\RabbitQueueSender;
use QueueManager\Strategy\ReceiverStrategyInterface;
use Traits\SingletonTrait;
use QueueManager\Senders\QueueSenderInterface;
use QueueManager\Strategy\RabbitReceiverStrategy;

class QueueManager implements QueueManagerInterface
{
	use SingletonTrait;

	/**
	 * @var RabbitReceiverStrategy
	 */
	private $receiver;

	/**
	 * @var array
	 */
	private $handlersTask = [];

	/**
	 * @var QueueSenderInterface
	 */
	private $sender;

    /**
     * @return ReceiverStrategyInterface
     */
	public function getReceiver(): ReceiverStrategyInterface
	{
		if (!$this->receiver instanceof ReceiverStrategyInterface) {
			$this->receiver = new RabbitReceiverStrategy();
		}

		return $this->receiver;
	}

	/**
	 * @param QueueSenderInterface $sender
	 * @return QueueManager
	 */
	public function setSender(QueueSenderInterface $sender): QueueManager
	{
		$this->sender = new $sender();
		return $this;
	}

	/**
	 * @param ReceiverStrategyInterface $receiverStrategy
	 * @return QueueManager
	 */
	public function setReceiver(ReceiverStrategyInterface $receiverStrategy): QueueManager
	{
		$this->receiver = $receiverStrategy;
		return $this;
	}

	/**
	 * @param string $name
	 * @param AbstractQueueHandler $queueClass
	 * @return QueueManager
	 */
	public function setQueueHandler(string $name, AbstractQueueHandler $queueClass): QueueManager
	{
		$this->handlersTask[$name] = $queueClass;
		return $this;
	}

	/**
	 * @param array $queueClasses
	 * @return QueueManager
	 */
	public function setQueueHandlers(array $queueClasses): QueueManager
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
     * @return QueueSenderInterface
     */
	public function sender(Queue $queue): QueueSenderInterface
	{
		if (!$this->sender instanceof QueueSenderInterface) {
			$this->sender = new RabbitQueueSender();
		}

		return $this->sender
			->setParams($queue)
			->build();
	}
}