<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.05.2018
 * Time: 23:12
 */

namespace RedisQueue;

class Server
{
	private $redis;

	public function __construct(\Redis $redis)
	{
		$this->redis = $redis;
	}

	/**
	 * @param string $status
	 */
	public function sendStatusTask(string $status): void
	{
		$this->redis->set($this->idHash, $status);
	}

	/**
	 * @return bool
	 */
	public function isDoWork(): bool
	{
		return $this->isDoWork;
	}

	/**
	 * @param int $pause
	 */
	public function pause(int $pause = 1000000): void
	{
		usleep($pause);
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
	 * @return RedisQueue
	 */
	public function setWorkProcess(): RedisQueue
	{
		$this->isDoWork = true;
		return $this;
	}

	/**
	 * @return OutputResult
	 */
	public function sendResult(): OutputResult
	{
		if (!$this->outputResult instanceof OutputResult){
			$this->outputResult = new OutputResult($this);
		}

		return $this->outputResult;
	}

	/**
	 * @return QueueEnvelope
	 */
	public function getStack(): QueueEnvelope
	{
		if ($this->isDoWork) {
			return $this->envelope->setBody('');
		}

		$body = $this->redis->lPop($this->queue->getName());

		if (!empty($body)) {
			$this->isDoWork = true;
		}

		$bodyJson     = json_decode($body, true);
		$this->idHash = $bodyJson['hash'];
		$this->envelope->setBody((string) $bodyJson['data']);

		return $this->envelope;
	}
}