<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 24.04.2018
 * Time: 14:46
 */

namespace QueueManager\Strategy;

interface ReceiverStrategyInterface
{
    /**
     * @return mixed
     */
    public function build();
}