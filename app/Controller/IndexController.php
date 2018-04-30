<?php

namespace App\Controller;

use QueueManager\Queue;
use QueueManager\QueueManager;
use System\Controller\AbstractController;
use App\Model\Dictionary\DictionaryRepository;
use App\Validator\SearchWordValidator;
use System\Database\DBManager\Mapping\ObjectMapper;
use System\Render;
use Widget\WidgetFactory;
use RedisQueue\RedisQueue;
use App\Model\Test\ModelTest;

class IndexController extends AbstractController
{
	/**
	 * @return Render
	 */
	public function indexAction(): Render
	{
	    $res = ObjectMapper::create()->toObject([
	        'userId'    => 14,
            'name'      => 'dd',
            'userName'  => 'dsdsdsdsd',
            'firstName' => 'aaaa',
            'lastName'  => 'ccccccc'
        ], ModelTest::class);

        ObjectMapper::create()->toArray($res);

		$dictRepos = new DictionaryRepository();

		WidgetFactory::run('test');

		$send = (new Queue())
			->setName('hello-queue')
			->setFlags('')
			->setExchangeName('hello-queue')
			->setRoutingKey('sendMail')
			->setData('Hello World Test')
			->setType(AMQP_EX_TYPE_DIRECT);

		QueueManager::create()
			->sender($send)
			->send();

		return $this->render('index.html', [
			'dictionaries' => $dictRepos->getAllDictionaries()
		]);
	}

	/**
	 * @param int $id
	 * @return Render
	 */
	public function dictionaryAction(int $id): Render
	{
		$dictRepos = new DictionaryRepository();

		return $this->render('random-word.html', [
			'dictionary' => $dictRepos->getDictionaryById($id)
		]);
	}

	/**
	 * @return Render
	 */
	public function searchWordAction(): Render
	{
		$queueRedis = new RedisQueue('127.0.0.1', 6379);

		$queue = new \RedisQueue\Queue();
		$queue->setName('testQueue');

		$queueRedis->setQueueParam($queue);
		$queueRedis->publish('Test Hello World');
		$queueRedis->disconnect();

		$dictRepos = new DictionaryRepository();
		$validator = new SearchWordValidator();

		if ($validator->isPost()) {
			if (!$validator->isValid()) {
				return $this->render('search-word.html');
			}

			$dictRepos->searchWord($_POST);
		}

		return $this->render('search-word.html');
	}
}
