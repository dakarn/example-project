<?php

include_once '../bootstrap.php';

use Queue\QueueManager;
use Console\Queue\HelloQueueHandler;

QueueManager::create()
	->setQueueHandler('hello', new HelloQueueHandler())
	->runHandler('hello');