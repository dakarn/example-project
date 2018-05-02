<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2018
 * Time: 15:51
 */

namespace RedisQueue;

use Psr\Log\InvalidArgumentException;

class OutputResult implements OutputResultInterface
{
	/**
	 * @var RedisQueue
	 */
	private $redisQueue;

	/**
	 * OutputResult constructor.
	 * @param RedisQueue $redisQueue
	 */
	public function __construct(RedisQueue $redisQueue)
	{
		$this->redisQueue = $redisQueue;
	}

	/**
	 * @return void
	 */
	public function done(): void
	{
		$this->redisQueue->setWorkDone();
		$this->redisQueue->sendStatusTask(true);
	}

	/**
	 * @param string $typeRequeue
	 */
	public function failed(string $typeRequeue = RedisQueue::QUEUE_DELETE): void
	{
		switch ($typeRequeue) {
			case RedisQueue::QUEUE_DELETE:
				break;
			case RedisQueue::REQUEUE:
				$this->redisQueue->publish($this->redisQueue->getEnvelope()->getBody());
				break;
			default:
				throw new InvalidArgumentException('The queue no support this failed type!');
		}

		$this->redisQueue->setWorkDone();
		$this->redisQueue->sendStatusTask(false);
	}
}