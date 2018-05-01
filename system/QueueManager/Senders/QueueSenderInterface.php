<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.04.2018
 * Time: 14:28
 */

namespace QueueManager\Senders;

use QueueManager\Queue;

interface QueueSenderInterface
{
	/**
	 * @param Queue $params
	 * @return QueueSenderInterface
	 */
	public function setParams(Queue $params): QueueSenderInterface;

	/**
	 * @return QueueSenderInterface
	 */
	public function build(): QueueSenderInterface;

	/**
	 * @return mixed
	 */
    public function send();
}