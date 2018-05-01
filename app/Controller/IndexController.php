<?php

namespace App\Controller;

use QueueManager\QueueModel;
use QueueManager\QueueManager;
use QueueManager\Senders\RedisQueueSender;
use System\Controller\AbstractController;
use App\Model\Dictionary\DictionaryRepository;
use App\Validator\SearchWordValidator;
use System\Database\DBManager\Mapping\ObjectMapper;
use System\Render;
use Widget\WidgetFactory;
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

		$send = (new QueueModel())
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
		$send = (new QueueModel())
			->setData('2' . time())
			->setName('testQueue');

		$manager = QueueManager::create()
			->setSender(new RedisQueueSender())
			->sender($send);

		for ($i = 0; $i < 2000; $i++) {
			$manager->send();
		}

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
