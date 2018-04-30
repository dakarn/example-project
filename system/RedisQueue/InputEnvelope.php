<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.04.2018
 * Time: 15:27
 */

namespace RedisQueue;

class InputEnvelope implements InputEnvelopeInterface
{
	/**
	 * @var string
	 */
	private $body = '';

	/**
	 * InputEnvelope constructor.
	 * @param string $body
	 */
	public function __construct(string $body)
	{
		$this->body = $body;
	}

	/**
	 * @return string
	 */
	public function getBody(): string
	{
		return $this->body;
	}

	/**
	 * @return bool
	 */
	public function isRecv(): bool
	{
		return !empty($this->body);
	}
}