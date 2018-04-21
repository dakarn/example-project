<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:49
 */
namespace Queue;

class Queue implements QueueInterface
{
	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var string
	 */
	private $dataForSend;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $exchangeName;

	/**
	 * @var int
	 */
	private $flags;

	/**
	 * @var string
	 */
	private $routingName;

	/**
	 * @param string $type
	 * @return Queue
	 */
	public function setType(string $type): Queue
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @param string $data
	 * @return Queue
	 */
	public function setData(string $data): Queue
	{
		$this->dataForSend = $data;
		return $this;
	}

	/**
	 * @param array $data
	 * @return Queue
	 */
	public function setDataAsArray(array $data): Queue
	{
		$this->dataForSend = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return $this;
	}

	/**
	 * @param string $exchangeName
	 * @return Queue
	 */
	public function setExchangeName(string $exchangeName): Queue
	{
		$this->exchangeName = $exchangeName;
		return $this;
	}

	/**
	 * @param string $flags
	 * @return Queue
	 */
	public function setFlags(string $flags): Queue
	{
		$this->flags = $flags;
		return $this;
	}

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
	 * @param string $routingKey
	 * @return Queue
	 */
	public function setRoutingKey(string $routingKey): Queue
	{
		$this->routingName = $routingKey;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getData(): string
	{
		return $this->dataForSend;
	}

	/**
	 * @return string
	 */
	public function getExchangeName(): string
	{
		return $this->exchangeName;
	}

	/**
	 * @return string
	 */
	public function getFlags(): string
	{
		return $this->flags;
	}

	/**
	 * @return string
	 */
	public function getRoutingKey(): string
	{
		return $this->routingName;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

}