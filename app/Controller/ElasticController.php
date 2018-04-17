<?php

namespace App\Controller;

use App\Model\Dictionary\Dictionary;
use App\Model\Dictionary\DictionaryRepository;
use System\Config;
use System\Controller\AbstractController;
use ElasticSearch\ElasticSearch;

class ElasticController extends AbstractController
{
	public function addIndexAction()
	{
		ElasticSearch::create()
			->setIndex('teacher')
			->setBody('PUT', [
				'mappings' => Config::get('elasticsearch', 'mappings')]
			)->execute();

		return $this->render('elastic/addindex.html');
	}

	public function removeIndexAction()
	{
		ElasticSearch::create()
			->setIndex('teacher')
			->deleteIndex();

		return $this->render('elastic/addindex.html');
	}

	public function indexerAction()
	{
		$dictRepos = new DictionaryRepository();
		$result    = $dictRepos->getAllDictionaries();
		$data      = '';

		/** @var Dictionary $item */
		foreach ($result as $item) {

			$data .= json_encode(['index' => ['_index' => 'teacher', '_type' => 'dictionary', '_id' => $item->getId()]]) . '\n';
			$data .= json_encode([
				'text'      => $item->getText(),
				'translate' => $item->getTranslate(),
				'type'      => $item->getType(),
				'level'     => $item->getLevel(),
				'audioFile' => $item->getAudioFile(),
			], JSON_UNESCAPED_UNICODE) . '\n';
		}

		ElasticSearch::create()
			->setPath('_bulk')
			->setBody('POST', $data)
			->execute()
			->getResult();

		return $this->render('elastic/addindex.html');
	}

	public function enterCommandAction()
	{
		ElasticSearch::create()
			->setIndex('teacher')
			->setType('dictionary')
			->setId(1)
			->get();
	}
}