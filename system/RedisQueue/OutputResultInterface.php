<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2018
 * Time: 15:53
 */

namespace RedisQueue;

interface OutputResultInterface
{
	/**
	 * OutputResultInterface constructor.
	 * @param RedisQueue $redisQueue
	 */
	public function __construct(RedisQueue $redisQueue);

	/**
	 * @return mixed
	 */
	public function done();

	/**
	 * @param string $typeRequeue
	 * @return mixed
	 */
	public function failed(string $typeRequeue);
}