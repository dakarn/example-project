<?php

include_once '../bootstrap-cli.php';

use QueueManager\QueueManager;
use App\Console\Queue\HelloQueueHandler;

QueueManager::create()
	->setQueueHandler('hello', new HelloQueueHandler())
	->runHandler('hello');