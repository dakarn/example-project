<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:49
 */

namespace RedisQueue;

class Queue implements QueueInterface
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param string $name
	 * @return Queue
	 */
	public function setName(string $name): Queue
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

}