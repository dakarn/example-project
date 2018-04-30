<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2018
 * Time: 15:09
 */

namespace RedisQueue;

interface RedisQueueInterface
{
	/**
	 * RedisQueueInterface constructor.
	 * @param string $host
	 * @param int $port
	 */
	public function __construct(string $host, int $port);

	/**
	 * @return bool
	 */
	public function isDoWork(): bool;

	/**
	 * @return RedisQueue
	 */
	public function setWorkDone(): RedisQueue;

	/**
	 * @return QueueEnvelope
	 */
	public function getEnvelope(): QueueEnvelope;

	/**
	 * @return RedisQueue
	 */
	public function setWorkProcess(): RedisQueue;

	/**
	 * @param Queue $queue
	 * @return RedisQueue
	 */
	public function setQueueParam(Queue $queue): RedisQueue;

	/**
	 * @param string $msg
	 */
	public function publish(string $msg);

	/**
	 * @return OutputResult
	 */
	public function result(): OutputResult;

	/**
	 * @return void
	 */
	public function pause(): void;

	/**
	 * @return QueueEnvelope
	 */
	public function getStack(): QueueEnvelope;

	/**
	 * @return void
	 */
	public function disconnect(): void;
}