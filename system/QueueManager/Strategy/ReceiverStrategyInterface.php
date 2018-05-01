<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 24.04.2018
 * Time: 14:46
 */

namespace QueueManager\Strategy;

use QueueManager\QueueModelModel;

interface ReceiverStrategyInterface
{
	/**
	 * @param QueueModelModel $params
	 * @return ReceiverStrategyInterface
	 */
	public function setParams(QueueModelModel $params): ReceiverStrategyInterface;

    /**
     * @return mixed
     */
    public function build();
}