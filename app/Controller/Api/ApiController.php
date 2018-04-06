<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.04.2018
 * Time: 1:35
 */

namespace Controller\Api;

use System\Controller\AbstractController;
use System\Response\Response;

class ApiController extends AbstractController
{
	public function addAction()
	{
		return new Response([], 'api', ['success']);
	}

	public function getAction()
	{
		return new Response(['errors' => ['text' => 'empty']], 'api', ['failed']);
	}

	public function updateAction()
	{
		return new Response([], 'api', ['success']);
	}

	public function deleteAction()
	{
		return new Response([], 'api', ['success']);
	}
}