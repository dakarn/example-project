<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:48
 */

namespace QueueManager\Strategy;

use AMQPConnection;
use QueueManager\Queue;

class RabbitReceiverStrategy extends AbstractQueueStrategy implements ReceiverStrategyInterface
{
	/**
	 * @param Queue $params
	 * @return RabbitReceiverStrategy
	 */
	public function setParams(Queue $params): self
	{
		$this->params = $params;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function build()
	{
		if (!$this->params instanceof Queue) {
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
		return [
			'amqp'     => $this->amqp,
			'channel'  => $this->channel,
			'exchange' => $this->exchange,
			'queue'    => $this->queueInst,
		];
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
		$this->queueInst->bind($this->params->getExchangeName(), $this->params->getRoutingKey());

		return $this;
	}
}