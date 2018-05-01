<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.05.2018
 * Time: 19:00
 */

namespace QueueManager\Senders;

use Configs\Config;
use RedisQueue\RedisQueue;
use QueueManager\Queue;
use RedisQueue\Queue as QueueModel;

class RedisQueueSender implements QueueSenderInterface
{
	/**
	 * @var Queue
	 */
	private $params;

	/**
	 * @var array|mixed|string
	 */
	private $configConnect = [];

	/**
	 * @var RedisQueue
	 */
	private $queueRedis;

	/**
	 * RabbitQueueSender constructor.
	 */
	public function __construct()
	{
		$this->configConnect = Config::get('redis-queue');
	}

	public function setParams(Queue $params): QueueSenderInterface
	{
		$this->params = $params;
		return $this;
	}

	/**
	 * @return QueueSenderInterface
	 */
	public function build(): QueueSenderInterface
	{
		$this->queueRedis = new RedisQueue($this->configConnect['host'], $this->configConnect['port']);

		$queue = new QueueModel();
		$queue->setName($this->params->getName());

		$this->queueRedis->setQueueParam($queue);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function send()
	{
		$time = time();

		$answer = $this->queueRedis->publish($time);
		$this->queueRedis->disconnect();

		return $answer;
	}
}