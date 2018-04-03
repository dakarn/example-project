<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 23:59
 */
namespace Console\Queue;

use Queue\AbstractQueueHandler;
use Queue\Queue;

class HelloQueueHandler extends AbstractQueueHandler
{
	public function before(): self
	{
		$this->queueParam = (new Queue())
			->setName('hello-queue')
			->setFlags('')
			->setExchangeName('hello-queue')
			->setRoutingKey('sendMail')
			->setType(AMQP_EX_TYPE_DIRECT);

		return $this;
	}

	public function run(\AMQPEnvelope $queue): bool
	{
		echo $queue->getBody() . PHP_EOL;
		return true;
	}

	public function after(): self
	{
		return $this;
	}
}