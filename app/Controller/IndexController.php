<?php

namespace App\Controller;

use QueueManager\QueueModelModel;
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

		$send = (new QueueModelModel())
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
		$send = (new QueueModelModel())->setName('testQueue');

		QueueManager::create()
			->setSender(new RedisQueueSender())
			->sender($send)
			->send();

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
