<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.03.2018
 * Time: 17:24
 */

namespace Service;

use System\Service\ServiceInterface;

class Writer implements ServiceInterface
{
	private $arguments = [];

	public function setArguments(array $arguments)
	{
		$this->arguments = $arguments;
	}

	public function getText()
	{
	}
}