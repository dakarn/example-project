<?php

namespace App\Controller;

use Queue\Queue;
use Queue\QueueManager;
use System\Controller\AbstractController;
use App\Model\Dictionary\DictionaryRepository;
use App\Validator\SearchWordValidator;
use Widget\WidgetFactory;

class IndexController extends AbstractController
{
	public function indexAction()
	{
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

	public function dictionaryAction(int $id)
	{
		$dictRepos = new DictionaryRepository();

		return $this->render('random-word.html', [
			'dictionary' => $dictRepos->getDictionaryById($id)
		]);
	}

	public function searchWordAction()
	{
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
