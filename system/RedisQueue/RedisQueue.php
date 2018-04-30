<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2018
 * Time: 15:09
 */

namespace RedisQueue;

class RedisQueue implements RedisQueueInterface
{
	/**
	 * @var \Redis
	 */
	private $redis;

	/**
	 * @var Queue
	 */
	private $queue;

	/**
	 * RedisQueue constructor.
	 * @param string $host
	 * @param int $port
	 */
	public function __construct(string $host, int $port)
	{
		$this->redis = new \Redis();
		$this->redis->connect($host, $port);
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

	public function bind()
	{

	}

	/**
	 * @param string $msg
	 */
	public function publish(string $msg)
	{
		$this->redis->lPush($this->queue->getName(), $msg);
	}

	/**
	 * @return InputEnvelope
	 */
	public function getStack()
	{
		$msg = $this->redis->rPop($this->queue->getName());
		return new InputEnvelope($msg);
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