<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.04.2018
 * Time: 1:14
 */

namespace Queue\Strategy;

use AMQPConnection;
use Queue\Queue;

abstract class AbstractQueueStrategy
{
	/**
	 * @var array
	 */
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
	 * AbstractQueueStrategy constructor.
	 * @param array $configConnect
	 */
	public function __construct(array $configConnect)
	{
		$this->configConnect = $configConnect;
	}

	/**
	 * @return mixed
	 */
	abstract public function build();

}