<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 23:59
 */
namespace App\Console\Queue;

use QueueManager\AbstractQueueHandler;
use QueueManager\Queue;

class HelloQueueHandler extends AbstractQueueHandler
{
	/**
	 * @return HelloQueueHandler
	 */
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

	/**
	 * @param \AMQPEnvelope $queue
	 * @return bool
	 */
	public function run(\AMQPEnvelope $queue): bool
	{
		echo $queue->getBody() . PHP_EOL;
		return true;
	}

	/**
	 * @return HelloQueueHandler
	 */
	public function after(): self
	{
		return $this;
	}
}