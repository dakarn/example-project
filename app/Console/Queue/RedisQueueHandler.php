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

	/**
	 * @return void
	 */
	public function prepare(): void
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

			$msg = $this->queueInst->server()->getStack();

			if ($msg->isReceived()) {
				echo $msg->getBody() . PHP_EOL;
				$this->queueInst->server()->sendResult()->done();
			}

			$this->queueInst->server()->pause(100);
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