<?php

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
     * @return Client
     */
    public function client(): Client;

    /**
     * @return Server
     */
    public function server(): Server;

    /**
     * @param Queue $queue
     * @return RedisQueue
     */
    public function setQueueParam(Queue $queue): RedisQueue;

    /**
     * @return Queue
     */
    public function getQueueParam(): Queue;

    /**
     *
     */
    public function disconnect(): void;
}