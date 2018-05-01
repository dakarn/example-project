<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2018
 * Time: 15:09
 */

namespace RedisQueue;

use Helper\Util;
use System\Logger\LogLevel;

class RedisQueue implements RedisQueueInterface
{
	/**
	 * @var string
	 */
	const REQUEUE = 'requeue';

	/**
	 * @var string
	 */
	const QUEUE_DELETE = 'queueDelete';

	/**
	 * @var \Redis
	 */
	private $redis;

	/**
	 * @var Queue
	 */
	private $queue;

	/**
	 * @var bool
	 */
	private $isDoWork = false;

	/**
	 * @var OutputResult
	 */
	private $outputResult;

	/**
	 * @var QueueEnvelope
	 */
	private $envelope;

	/**
	 * RedisQueue constructor.
	 * @param string $host
	 * @param int $port
	 */
	public function __construct(string $host, int $port)
	{
		try {
			$this->redis = new \Redis();
			$this->redis->connect($host, $port);
		} catch (\RedisException $e) {
			Util::log(LogLevel::EMERGENCY, $e->getMessage());
		}
	}

	/**
	 * @return bool
	 */
	public function isDoWork(): bool
	{
		return $this->isDoWork;
	}

	/**
	 * @return RedisQueue
	 */
	public function setWorkDone(): RedisQueue
	{
		$this->isDoWork = false;
		return $this;
	}

	/**
	 * @return QueueEnvelope
	 */
	public function getEnvelope(): QueueEnvelope
	{
		return $this->envelope;
	}

	/**
	 * @return RedisQueue
	 */
	public function setWorkProcess(): RedisQueue
	{
		$this->isDoWork = true;
		return $this;
	}

	/**
	 * @param Queue $queue
	 * @return RedisQueue
	 */
	public function setQueueParam(Queue $queue): RedisQueue
	{
		$this->queue = $queue;
		return $this;
	}

	/**
	 * @param string $msg
	 */
	public function publish(string $msg)
	{
		$this->redis->rPush($this->queue->getName(), $msg);
	}

	/**
	 * @return OutputResult
	 */
	public function result(): OutputResult
	{
		if (!$this->outputResult instanceof OutputResult){
			$this->outputResult = new OutputResult($this);
		}

		return $this->outputResult;
	}

	/**
	 * @return void
	 */
	public function pause(): void
	{
		usleep(1000000);
	}

	/**
	 * @return QueueEnvelope
	 */
	public function getStack(): QueueEnvelope
	{
		if (!$this->envelope instanceof QueueEnvelope){
			$this->envelope = new QueueEnvelope();
		}

		if ($this->isDoWork) {
			return $this->envelope->setBody('');
		}

		$body = $this->redis->lPop($this->queue->getName());

		if (!empty($body)) {
			$this->isDoWork = true;
		}

		return $this->envelope->setBody($body);
	}

	/**
	 * @return void
	 */
	public function disconnect(): void
	{
		if ($this->redis instanceof \Redis) {
			$this->redis->close();
		}
	}
}