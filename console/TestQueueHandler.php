<?php

include_once '../bootstrap-cli.php';

$queueRedis = new \RedisQueue\RedisQueue('127.0.0.1', 6379);

$queue = new \RedisQueue\Queue();
$queue->setName('testQueue');

$queueRedis->setQueueParam($queue);

while (true) {

	$msg = $queueRedis->getStack();

	if ($msg->isReceived()) {
		echo $msg->getBody() . PHP_EOL;
		$queueRedis->result()->done();
	}

	$queueRedis->pause();
}

