<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 16:38
 */

namespace Queue\Senders;

use Queue\Strategy\AbstractQueueStrategy;
use Queue\Queue;

class RabbitQueueSender extends AbstractQueueStrategy
{
	/**
	 * @param Queue $params
	 * @return RabbitQueueSender
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
		$this->amqp = new \AMQPConnection($this->configConnect);
		$this->amqp->connect();

		$this->channel = new \AMQPChannel($this->amqp);

		$this->exchange = new \AMQPExchange($this->channel);

		$this->exchange->setName($this->params->getExchangeName());
		$this->exchange->setType($this->params->getType());
		$this->exchange->declareExchange();

		$this->queueInst = new \AMQPQueue($this->channel);
		$this->queueInst->setName($this->params->getName());

		return $this;
	}

	/**
	 * @return bool
	 */
	public function send()
	{
		$result = $this->exchange->publish($this->params->getData(), $this->params->getRoutingKey());
		$this->amqp->disconnect();

		return $result;
	}
}