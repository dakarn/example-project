<?php

namespace Controller;

use Queue\Queue;
use Queue\QueueManager;
use Validator\AddWordValidator;
use System\Controller\AbstractController;
use Model\Dictionary\DictionaryRepository;

class IndexController extends AbstractController
{
	public function indexAction()
	{
		$dictRepos = new DictionaryRepository();

		//$this->redirectToRoute('indexer', ['id' => 5, 'red' => 4]);

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
		$validator = new AddWordValidator();

		if (!$validator->validate()) {
			return $this->render('index.html', [
				'errors'       => $validator->getErrors(),
				'dictionaries' => $dictRepos->getAllDictionaries()
			]);
		}

		$dictRepos = new DictionaryRepository();
		$dictRepos->searchWord($_POST);

		return $this->render('index.html');
	}
}
