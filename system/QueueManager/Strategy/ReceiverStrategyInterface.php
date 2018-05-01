<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 24.04.2018
 * Time: 14:46
 */

namespace QueueManager\Strategy;

use QueueManager\Queue;

interface ReceiverStrategyInterface
{
	/**
	 * @param Queue $params
	 * @return ReceiverStrategyInterface
	 */
	public function setParams(Queue $params): ReceiverStrategyInterface;

    /**
     * @return mixed
     */
    public function build();
}