<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:48
 */

namespace Queue;

use AMQPConnection;

class QueueStrategy
{
	/**
	 * @var AMQPConnection
	 */
	protected $amqp;

	/**
	 * @var \AMQPExchange
	 */
	protected $exchange;

	/**
	 * @var \AMQPChannel
	 */
	protected $channel;

	/**
	 * @var \AMQPQueue
	 */
	protected $queueInst;

	/**
	 * @var array
	 */
	protected $configConnect = [];

	/**
	 * @var Queue
	 */
	protected $params;

	/**
	 * @var string
	 */
	protected $type;

	public function __construct(array $configConnect, string $type)
	{
		$this->configConnect = $configConnect;
	}

	public function build()
	{
		$this->connection();
		$this->createChannel();
		$this->createExchange();
		$this->createQueue();
	}

	public function setParams(Queue $params): self
	{
		$this->params = $params;
		return $this;
	}

	public function connection(): self
	{
		$this->amqp = new AMQPConnection($this->configConnect);
		$this->amqp->connect();

		return $this;
	}

	public function createChannel(): self
	{
		$this->channel = new \AMQPChannel($this->amqp);
		return $this;
	}

	public function createExchange(): self
	{
		$this->exchange = new \AMQPExchange($this->channel);

		$this->exchange->setName('hello-queue');
		$this->exchange->setType(AMQP_EX_TYPE_DIRECT);
		$this->exchange->declareExchange();

		return $this;
	}

	public function createQueue(): self
	{
		$this->queueInst = new \AMQPQueue($this->channel);

		$this->queueInst->setName('hello-queue');
		$this->queueInst->bind('hello-queue', 'sendMail');

		return $this;
	}

	public function sendSuccess(\AMQPEnvelope $msg)
	{
		$this->queueInst->ack($msg->getDeliveryTag());
	}

	public function sendFailed(\AMQPEnvelope $msg)
	{
		$this->queueInst->nack($msg->getDeliveryTag(), AMQP_REQUEUE);
	}

	public function getCreationObject(): array
	{
		return [
			'amqp'     => $this->amqp,
			'channel'  => $this->channel,
			'exchange' => $this->exchange,
			'queue'    => $this->queueInst,
		];
	}
}