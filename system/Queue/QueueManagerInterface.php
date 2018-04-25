<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 24.04.2018
 * Time: 14:45
 */

namespace Queue;

use Queue\Strategy\ReceiverStrategyInterface;

interface QueueManagerInterface
{
    /**
     * @return ReceiverStrategyInterface
     */
    public function getReceiverStrategy(): ReceiverStrategyInterface;

    /**
     * @param string $name
     * @param AbstractQueueHandler $queueClass
     * @return QueueManager
     */
    public function setQueueHandler(string $name, AbstractQueueHandler $queueClass): QueueManager;

    /**
     * @param array $queueClasses
     * @return QueueManager
     */
    public function setQueueHandlers(array $queueClasses): QueueManager;

    /**
     * @return bool
     */
    public function runHandlers(): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function runHandler(string $name): bool;

    /**
     * @param Queue $queue
     * @return QueueSenderInterface
     */
    public function sender(Queue $queue): QueueSenderInterface;
}