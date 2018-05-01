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
use QueueManager\QueueManager;
use QueueManager\Strategy\RabbitReceiverStrategy;

class HelloQueueHandler extends AbstractQueueHandler
{
	/**
	 * @var RabbitReceiverStrategy
	 */
	private $strategy;

	public function prepare()
	{
		$this->queueParam = (new Queue())
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
		$this->amqp      = $instanceConnect['amqp'];
		$this->channel   = $instanceConnect['channel'];
		$this->exchange  = $instanceConnect['exchange'];
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

					if (1 < 2) {
						$this->strategy->sendSuccess($msg);
					} else {
						$this->strategy->sendFailed($msg);
					}
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