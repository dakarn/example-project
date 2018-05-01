<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 23:59
 */
namespace App\Console\Queue;

use QueueManager\AbstractQueueHandler;
use QueueManager\QueueModel;
use QueueManager\QueueManager;
use QueueManager\Strategy\RabbitReceiverStrategy;

class HelloQueueHandler extends AbstractQueueHandler
{
	/**
	 * @var RabbitReceiverStrategy
	 */
	private $strategy;

	/**
	 * @var \AMQPQueue
	 */
	private $queueInst;

	/**
	 * @return void
	 */
	public function prepare(): void
	{
		$this->queueParam = (new QueueModel())
			->setName('hello-queue')
			->setFlags('')
			->setExchangeName('hello-queue')
			->setRoutingKey('sendMail')
			->setType(AMQP_EX_TYPE_DIRECT);

	}

	/**
	 * @return HelloQueueHandler
	 */
	public function before(): self
	{
		$this->strategy = QueueManager::create()->getReceiver();
		$this->strategy
			->setParams($this->queueParam)
			->build();

		$instanceConnect = $this->strategy->getCreationObject();
		$this->queueInst = $instanceConnect['queue'];

		return $this;
	}

	/**
	 * @return bool
	 */
	public function run(): bool
	{
		try {

			while (true) {
				if ($msg = $this->queueInst->get()) {

					echo $msg->getBody() . PHP_EOL;
					$this->strategy->sendSuccess($msg);
				}
			}

			$this->after();

		}  catch(\Throwable $e) {
			echo $e->getMessage() . PHP_EOL;
		}

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