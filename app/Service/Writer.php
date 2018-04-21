<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.03.2018
 * Time: 17:24
 */

namespace App\Service;

use System\Service\ServiceInterface;

class Writer implements ServiceInterface
{
	/**
	 * @var array
	 */
	private $arguments = [];

	/**
	 * @param array $arguments
	 */
	public function setArguments(array $arguments)
	{
		$this->arguments = $arguments;
	}

	public function getText()
	{
	}
}