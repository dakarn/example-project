<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.05.2018
 * Time: 19:00
 */

namespace QueueManager\Strategy;

use Configs\Config;
use QueueManager\QueueModelModel;
use RedisQueue\Queue as QueueRedis;
use RedisQueue\RedisQueue;

class RedisReceiverStrategy implements ReceiverStrategyInterface
{
	/**
	 * @var QueueModelModel
	 */
	private $params;

	private $queueRedis;

	private $configConnect;

	public function __construct()
	{
		$this->configConnect = Config::get('redis-queue');
	}

	/**
	 * @param QueueModelModel $params
	 * @return ReceiverStrategyInterface
	 */
	public function setParams(QueueModelModel $params): ReceiverStrategyInterface
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