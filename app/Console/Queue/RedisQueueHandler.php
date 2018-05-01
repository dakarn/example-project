<?php

namespace App\Console\Queue;

use QueueManager\AbstractQueueHandler;
use QueueManager\QueueModel;
use QueueManager\QueueManager;
use QueueManager\Strategy\RedisReceiverStrategy;
use RedisQueue\RedisQueue;

class RedisQueueHandler extends AbstractQueueHandler
{
	/**
	 * @var RedisReceiverStrategy
	 */
	private $strategy;

	/**
	 * @var RedisQueue
	 */
	protected $queueInst;

	public function prepare()
	{
		$this->queueParam = (new QueueModel())->setName('testQueue');
	}

	/**
	 * @return RedisQueueHandler
	 */
	public function before(): self
	{
		$this->strategy = QueueManager::create()->getReceiver();
		$this->strategy
			->setParams($this->queueParam)
			->build();

		$this->queueInst = $this->strategy->getCreationObject()['redis'];

		return $this;
	}

	/**
	 * @return bool
	 */
	public function run(): bool
	{
		while (true) {

			$msg = $this->queueInst->getStack();

			if ($msg->isReceived()) {
				echo $msg->getBody() . PHP_EOL;
				$this->queueInst->result()->done();
			}

			$this->queueInst->pause(500);
		}

		return true;
	}

	/**
	 * @return RedisQueueHandler
	 */
	public function after(): self
	{
		return $this;
	}
}