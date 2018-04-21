<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.03.2018
 * Time: 21:27
 */

namespace Http\Request;

use Traits\SingletonTrait;

final class HTTPClient
{
	use SingletonTrait;

	/**
	 * @var array
	 */
	private $headers = [];

	/**
	 * @var array
	 */
	private $paramCurl = [];

	/**
	 * @var string
	 */
	private $result;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @param Request $request
	 */
	public function doRequest(Request $request): void
	{
		$this->request = $request;

		$this->buildQuery(new RequestBuilder());
		$this->execute();
	}

	/**
	 * @param RequestBuilderInterface $builder
	 */
	private function buildQuery(RequestBuilderInterface $builder): void
	{
		$this->headers          = $this->request->getHeaders();
		$this->paramCurl['url'] = $this->request->getHost();
	}

	/**
	 * @varv oid
	 */
	private function execute(): void
	{
		$ch           = curl_init($this->paramCurl['url']);
		$this->result = curl_exec($ch);
		curl_close($ch);
	}

	/**
	 * @return string
	 */
	public function getResult(): string
	{
		return $this->result;
	}
}