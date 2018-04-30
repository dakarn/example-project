<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 1:03
 */

namespace QueueManager;

use AMQPConnection;

abstract class AbstractQueueHandler
{
	/**
	 * @var Queue
	 */
	protected $queueParam;

	/**
	 * @var AMQPConnection
	 */
	protected $amqp;

	/**
	 * @var \AMQPExchange
	 */
	protected $exchange;

	/**
	 * @var \AMQPChannel
	 */
	protected $channel;

	/**
	 * @var \AMQPQueue
	 */
	protected $queueInst;

	/**
	 * @return AbstractQueueHandler
	 */
	public function prepareObject(): self
	{
		$this->before();

		$strategy = QueueManager::create()->getReceiverStrategy();
		$strategy
			->setParams($this->queueParam)
			->build();

		$instanceConnect = $strategy->getCreationObject();
		$this->amqp      = $instanceConnect['amqp'];
		$this->channel   = $instanceConnect['channel'];
		$this->exchange  = $instanceConnect['exchange'];
		$this->queueInst = $instanceConnect['queue'];

		return $this;
	}

	/**
	 * @return void
	 */
	public function loopObserver()
	{
		try {

			while (true) {
				if ($msg = $this->queueInst->get()) {

					if ($this->run($msg)) {
						QueueManager::create()->getReceiverStrategy()->sendSuccess($msg);
					} else {
						QueueManager::create()->getReceiverStrategy()->sendFailed($msg);
					}
				}
			}

			$this->after();

		}  catch(\Throwable $e) {
			echo $e->getMessage() . PHP_EOL;
		}
	}

	/**
	 * @return mixed
	 */
	abstract public function before();

	/**
	 * @param \AMQPEnvelope $queue
	 * @return bool
	 */
	abstract public function run(\AMQPEnvelope $queue): bool;

	/**
	 * @return mixed
	 */
	abstract public function after();
}