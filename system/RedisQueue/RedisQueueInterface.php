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
}