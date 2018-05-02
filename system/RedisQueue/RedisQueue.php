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
	 * @var string
	 */
	const FORMAT_SEND = '{"hash": "%s", "data": %s}';

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
	 * @var string
	 */
	private $idHash = '';

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

			if (!$this->envelope instanceof QueueEnvelope){
				$this->envelope = new QueueEnvelope();
			}
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
	 * @return QueueEnvelope
	 */
	public function getEnvelope(): QueueEnvelope
	{
		return $this->envelope;
	}

	/**
	 * @param string $msg
	 * @return int
	 */
	public function publish(string $msg): int
	{
		return $this->redis->rPush($this->queue->getName(), $this->generateData($msg));
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
	 * @param int $timeout second
	 * @return string
	 */
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
	 * @param string $status
	 */
	public function sendStatusTask(string $status): void
	{
		$this->redis->set($this->idHash, $status);
	}

	/**
	 * @param int $pause
	 */
	public function pause(int $pause = 1000000): void
	{
		usleep($pause);
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

	/**
	 * @return void
	 */
	public function disconnect(): void
	{
		if ($this->redis instanceof \Redis) {
			$this->redis->close();
		}
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