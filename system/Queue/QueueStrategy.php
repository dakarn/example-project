<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:48
 */

namespace Queue;

use AMQPConnection;

class QueueStrategy implements QueueStrategyInterface
{
	const QUEUE_TYPE = [
		'sender',
		'receiver',
	];

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

	public function __construct(array $configConnect)
	{
		$this->configConnect = $configConnect;
	}

	public function build(): self
	{
		$this->isBuild = true;

		$this->connection();
		$this->createChannel();
		$this->createExchange();
		$this->createQueue();

		return $this;
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

		$this->exchange->setName($this->params->getExchangeName());
		$this->exchange->setType($this->params->getType());
		$this->exchange->declareExchange();

		return $this;
	}

	public function createQueue(): self
	{
		$this->queueInst = new \AMQPQueue($this->channel);

		$this->queueInst->setName($this->params->getName());

		if ($this->isReceiver()) {
			$this->queueInst->bind($this->params->getExchangeName(), $this->params->getRoutingKey());
		}

		return $this;
	}

	public function send(): self
	{
		if ($this->isSender()) {
			$this->exchange->publish($this->params->getData(), $this->params->getRoutingKey());
			$this->amqp->disconnect();
		}

		return $this;
	}

	public function isSender(): bool
	{
		return $this->type === self::QUEUE_TYPE[0];
	}

	public function setAsSender(): self
	{
		$this->type = self::QUEUE_TYPE[0];
		return $this;
	}

	public function setAsReceiver(): self
	{
		$this->type = self::QUEUE_TYPE[1];
		return $this;
	}

	public function isReceiver(): bool
	{
		return $this->type === self::QUEUE_TYPE[1];
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