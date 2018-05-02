<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.05.2018
 * Time: 23:11
 */

namespace RedisQueue;

class Client
{
	private $redis;

	public function __construct(\Redis $redis)
	{
		$this->redis = $redis;
	}

	public function publish(string $msg): int
	{
		return $this->redis->rPush($this->queue->getName(), $this->generateData($msg));
	}

	public function getResult(int $timeout = 5): string
	{
		$currentTime = 0;
		$timeout     = $timeout * 100000;

		while (true) {

			if ($currentTime >= $timeout) {
				return '';
			}

			$value = $this->redis->get($this->idHash);

			if ($value !== false) {
				return $value;
			}

			$currentTime += 100;
			usleep(100);
		}

		return '';
	}

	/**
	 * @return QueueEnvelope
	 */
	public function getEnvelope(): QueueEnvelope
	{
		return $this->envelope;
	}

	/**
	 * @param string $msg
	 * @return string
	 */
	private function generateData(string $msg): string
	{
		$this->idHash = microtime(true) . random_int(1, 1000);
		return sprintf(self::FORMAT_SEND, $this->idHash, $msg);
	}
}