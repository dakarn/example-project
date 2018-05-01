<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:48
 */

namespace QueueManager\Strategy;

use AMQPConnection;
use Configs\Config;
use QueueManager\QueueModel;

class RabbitReceiverStrategy implements ReceiverStrategyInterface
{
	/**
	 * @var \AMQPConnection
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
	 * @var QueueModel
	 */
	private $params;

	public function __construct()
	{
		$this->configConnect = Config::get('rabbit');
	}

	/**
	 * @param QueueModel $params
	 * @return ReceiverStrategyInterface
	 */
	public function setParams(QueueModel $params): ReceiverStrategyInterface
	{
		$this->params = $params;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function build()
	{
		if (!$this->params instanceof QueueModel) {
			throw new \LogicException('Object data for connection with QueueServer do not filled!');
		}

		$this->connection()
			 ->createChannel()
			 ->createExchange()
			 ->createQueue();

		return $this;
	}

	/**
	 * @param \AMQPEnvelope $msg
	 */
	public function sendSuccess(\AMQPEnvelope $msg)
	{
		$this->queueInst->ack($msg->getDeliveryTag());
	}

	/**
	 * @param \AMQPEnvelope $msg
	 */
	public function sendFailed(\AMQPEnvelope $msg)
	{
		$this->queueInst->nack($msg->getDeliveryTag(), AMQP_REQUEUE);
	}

	/**
	 * @return array
	 */
	public function getCreationObject(): array
	{
		return ['queue' => $this->queueInst];
	}

	/**
	 * @return RabbitReceiverStrategy
	 */
	private function connection(): self
	{
		$this->amqp = new AMQPConnection($this->configConnect);
		$this->amqp->connect();

		return $this;
	}

	/**
	 * @return RabbitReceiverStrategy
	 */
	private function createChannel(): self
	{
		$this->channel = new \AMQPChannel($this->amqp);
		return $this;
	}

	/**
	 * @return RabbitReceiverStrategy
	 */
	private function createExchange(): self
	{
		$this->exchange = new \AMQPExchange($this->channel);

		$this->exchange->setName($this->params->getExchangeName());
		$this->exchange->setType($this->params->getType());
		$this->exchange->declareExchange();

		return $this;
	}

	/**
	 * @return RabbitReceiverStrategy
	 */
	private function createQueue(): self
	{
		$this->queueInst = new \AMQPQueue($this->channel);

		$this->queueInst->setName($this->params->getName());
		$this->queueInst->declareQueue();
		$this->queueInst->bind($this->params->getExchangeName(), $this->params->getRoutingKey());

		return $this;
	}
}