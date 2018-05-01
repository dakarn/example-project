<?php

include_once '../bootstrap-cli.php';

use QueueManager\QueueManager;
use App\Console\Queue\RedisQueueHandler;
use QueueManager\Strategy\RedisReceiverStrategy;

QueueManager::create()
	->setReceiver(new RedisReceiverStrategy())
	->setQueueHandler('redis', new RedisQueueHandler())
	->runHandler('redis');