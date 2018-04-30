<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 23:52
 */

namespace QueueManager;

interface QueueInterface
{
	/**
	 * @param string $name
	 * @return Queue
	 */
	public function setName(string $name): Queue;

	/**
	 * @param string $name
	 * @return Queue
	 */
	public function setData(string $name): Queue;

	/**
	 * @param string $routingKey
	 * @return Queue
	 */
	public function setRoutingKey(string $routingKey): Queue;

	/**
	 * @param string $type
	 * @return Queue
	 */
	public function setType(string $type): Queue;

	/**
	 * @param string $flags
	 * @return Queue
	 */
	public function setFlags(string $flags): Queue;

	/**
	 * @param string $exchangeName
	 * @return Queue
	 */
	public function setExchangeName(string $exchangeName): Queue;

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @return string
	 */
	public function getRoutingKey(): string;

	/**
	 * @return string
	 */
	public function getType(): string;

	/**
	 * @return string
	 */
	public function getData(): string;

	/**
	 * @return string
	 */
	public function getFlags(): string;

	/**
	 * @return string
	 */
	public function getExchangeName(): string;
}