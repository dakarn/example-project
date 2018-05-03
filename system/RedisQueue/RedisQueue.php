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
	 * @var \Redis
	 */
	private $redis;

	/**
	 * @var Queue
	 */
	private $queue;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @var Server
	 */
	private $server;

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

			$this->client   = new Client($this->redis, $this);
			$this->server   = new Server($this->redis, $this);
		} catch (\Exception $e) {
			Util::log(LogLevel::EMERGENCY, $e->getMessage());
		}
	}

    /**
     * @return Client
     */
    public function client(): Client
    {
        return $this->client;
    }

    /**
     * @return Server
     */
    public function server(): Server
    {
        return $this->server;
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
     * @return Queue
     */
    public function getQueueParam(): Queue
    {
        return $this->queue;
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