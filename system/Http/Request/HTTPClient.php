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

		$this->buildQuery();
		$this->execute();
	}

	/**
	 * @var void
	 */
	private function buildQuery(): void
	{
		$this->headers          = $this->request->getHeaders();
		$this->paramCurl['url'] = $this->request->getHost();
	}

	/**
	 * @varv oid
	 */
	private function execute(): void
	{
		$ch = curl_init($this->paramCurl['url']);
		$this->result = curl_exec($ch);
		curl_close($ch);
	}
}