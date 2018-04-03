<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.04.2018
 * Time: 16:38
 */

namespace Queue;

class QueueSender
{
	/**
	 * @var Queue
	 */
	private $queue;

	/** @var  QueueStrategy */
	private $strategy;

	public function __construct(Queue $queue, array $configConnect)
	{
		$this->queue = $queue;
		$this->build();
	}

	public function build()
	{
		$this->strategy = QueueManager::create()->getStrategy()
			->setAsSender()
			->setParams($this->queue)
			->build();
	}

	public function send()
	{
		$this->strategy->send();
	}
}