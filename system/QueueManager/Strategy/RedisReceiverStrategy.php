<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.05.2018
 * Time: 19:00
 */

namespace QueueManager\Strategy;

use Configs\Config;
use QueueManager\Queue;
use RedisQueue\Queue as QueueRedis;
use RedisQueue\RedisQueue;

class RedisReceiverStrategy implements ReceiverStrategyInterface
{
	/**
	 * @var Queue
	 */
	private $params;

	private $queueRedis;

	private $configConnect;

	public function __construct()
	{
		$this->configConnect = Config::get('redis-queue');
	}

	/**
	 * @param Queue $params
	 * @return ReceiverStrategyInterface
	 */
	public function setParams(Queue $params): ReceiverStrategyInterface
	{
		$this->params = $params;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function build()
	{
		$this->queueRedis = new RedisQueue($this->configConnect['host'], $this->configConnect['port']);

		$queue = new QueueRedis();
		$queue->setName('testQueue');

		$this->queueRedis->setQueueParam($queue);

		return $this;
	}

	/**
	 * @return array
	 */
	public function getCreationObject(): array
	{
		return [
			'redis' => $this->queueRedis,
		];
	}
}