<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.04.2018
 * Time: 1:35
 */

namespace App\Controller\Api\V1;

use App\Model\Dictionary\DictionaryRepository;
use Http\Response\API;
use Http\Response\Response;
use System\Controller\AbstractController;
use App\Validator\SearchWordValidator;

class ApiController extends AbstractController
{
	/**
	 * @return Response
	 */
	public function addAction(): Response
	{
		return $this->response(new API([], []));
	}

	/**
	 * @return Response
	 */
	public function getAction(): Response
	{
		return $this->responseApiOK(['sdsd' => 'sdsd']);
	}

	/**
	 * @return Response
	 */
	public function updateAction(): Response
	{
		return $this->response(new API([], []));
	}

	/**
	 * @return Response
	 */
	public function deleteAction(): Response
	{
		return $this->response(new API([], []));
	}

	/**
	 * @return Response
	 */
	public function searchWordAction(): Response
	{
		$dictRepos = new DictionaryRepository();
		$validator = new SearchWordValidator();

		if ($validator->isPost()) {
			if (!$validator->isValid()) {
			}

			$dictRepos->searchWord($_POST);
		}

		return $this->response(new API([], []));
	}
}