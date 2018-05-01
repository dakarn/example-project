<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 0:49
 */

namespace QueueManager;

class QueueModelModel implements QueueModelInterface
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
	 * @return QueueModelModel
	 */
	public function setType(string $type): QueueModelModel
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * @param string $data
	 * @return QueueModelModel
	 */
	public function setData(string $data): QueueModelModel
	{
		$this->dataForSend = $data;
		return $this;
	}

	/**
	 * @param array $data
	 * @return QueueModelModel
	 */
	public function setDataAsArray(array $data): QueueModelModel
	{
		$this->dataForSend = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return $this;
	}

	/**
	 * @param string $exchangeName
	 * @return QueueModelModel
	 */
	public function setExchangeName(string $exchangeName): QueueModelModel
	{
		$this->exchangeName = $exchangeName;
		return $this;
	}

	/**
	 * @param string $flags
	 * @return QueueModelModel
	 */
	public function setFlags(string $flags): QueueModelModel
	{
		$this->flags = $flags;
		return $this;
	}

	/**
	 * @param string $name
	 * @return QueueModelModel
	 */
	public function setName(string $name): QueueModelModel
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string $routingKey
	 * @return QueueModelModel
	 */
	public function setRoutingKey(string $routingKey): QueueModelModel
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