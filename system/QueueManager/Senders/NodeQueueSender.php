<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.04.2018
 * Time: 0:56
 */

namespace QueueManager\Senders;

use QueueManager\QueueModelModel;

class NodeQueueSender implements QueueSenderInterface
{
	public function setParams(QueueModelModel $params): QueueSenderInterface
	{
		return $this;
	}

	public function build(): QueueSenderInterface
	{
		return $this;
	}

	public function send(): bool
    {
		return true;
    }
}