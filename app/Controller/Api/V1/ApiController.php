<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.04.2018
 * Time: 1:35
 */

namespace Controller\Api\V1;

use Model\Dictionary\DictionaryRepository;
use System\Controller\AbstractController;
use System\Response\Response;
use Validator\SearchWordValidator;

class ApiController extends AbstractController
{
	public function addAction()
	{
		return $this->response([], 'api', ['success']);
	}

	public function getAction()
	{
		return $this->response([], 'api', ['success']);
	}

	public function updateAction()
	{
		return $this->response([], 'api', ['success']);
	}

	public function deleteAction()
	{
		return $this->response([], 'api', ['success']);
	}

	public function searchWordAction()
	{
		$dictRepos = new DictionaryRepository();
		$validator = new SearchWordValidator();

		if ($validator->isPost()) {
			if (!$validator->isValid()) {
			}

			$dictRepos->searchWord($_POST);
		}

		return $this->response([], 'api', ['success']);
	}
}