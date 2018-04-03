<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 16:38
 */

namespace Queue;

use AMQPConnection;

class QueueSender
{
	/**
	 * @var AMQPConnection
	 */
	private $amqp;

	/**
	 * @var \AMQPExchange
	 */
	private $exchange;

	/**
	 * @var \AMQPChannel
	 */
	private $channel;

	/**
	 * @var \AMQPQueue
	 */
	private $queueInst;

	/**
	 * @var array
	 */
	private $configConnect = [];

	/**
	 * @var Queue
	 */
	private $queue;

	public function __construct(Queue $queue, array $configConnect)
	{
		$this->queue = $queue;
		$this->build();
	}

	public function build()
	{
		$this->amqp = new AMQPConnection($this->configConnect);
		$this->amqp->connect();

		$this->channel  = new \AMQPChannel($this->amqp);
		$this->exchange = new \AMQPExchange($this->channel);

		$this->exchange->setName($this->queue->getExchangeName());
		$this->exchange->setType($this->queue->getType());
		$this->exchange->declareExchange();

		$this->queueInst = new \AMQPQueue($this->channel);

		$this->queueInst->setName($this->queue->getName());
	}

	public function send()
	{
		$this->exchange->publish($this->queue->getData(), $this->queue->getRoutingKey());
		$this->amqp->disconnect();
	}
}