<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.04.2018
 * Time: 23:52
 */

namespace QueueManager;

interface QueueModelInterface
{
	/**
	 * @param string $name
	 * @return QueueModelModel
	 */
	public function setName(string $name): QueueModelModel;

	/**
	 * @param string $name
	 * @return QueueModelModel
	 */
	public function setData(string $name): QueueModelModel;

	/**
	 * @param string $routingKey
	 * @return QueueModelModel
	 */
	public function setRoutingKey(string $routingKey): QueueModelModel;

	/**
	 * @param string $type
	 * @return QueueModelModel
	 */
	public function setType(string $type): QueueModelModel;

	/**
	 * @param string $flags
	 * @return QueueModelModel
	 */
	public function setFlags(string $flags): QueueModelModel;

	/**
	 * @param string $exchangeName
	 * @return QueueModelModel
	 */
	public function setExchangeName(string $exchangeName): QueueModelModel;

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