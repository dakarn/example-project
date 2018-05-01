<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.04.2018
 * Time: 14:28
 */

namespace QueueManager\Senders;

use QueueManager\QueueModelModel;

interface QueueSenderInterface
{
	/**
	 * @param QueueModelModel $params
	 * @return QueueSenderInterface
	 */
	public function setParams(QueueModelModel $params): QueueSenderInterface;

	/**
	 * @return QueueSenderInterface
	 */
	public function build(): QueueSenderInterface;

	/**
	 * @return mixed
	 */
    public function send();
}