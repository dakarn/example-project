<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.03.2018
 * Time: 1:01
 */

namespace ElasticSearch;

use Exception\HttpException;
use Configs\Config;

class ElasticHttp
{
	/**
	 * @var array
	 */
	const ALLOW_METHOD = [
		'POST'   => '',
		'PUT'    => '',
		'GET'    => '',
		'DELETE' => '',
		'HEAD'   => '',
	];

	/**
	 * @var bool
	 */
	private $isPretty = false;

	/**
	 * @var bool
	 */
	private $isGet = false;

	/**
	 * @var bool
	 */
	private $isSearch = false;

	/**
	 * @var string
	 */
	private $url = '';

	/**
	 * @var bool
	 */
	private $isPost = false;

	/**
	 * @var bool
	 */
	private $isPut = false;

	/**
	 * @var bool
	 */
	private $isDelete = false;

	/**
	 * @var array
	 */
	private $query = [];

	/**
	 * @param bool $isPretty
	 * @return ElasticHttp
	 */
	public function setPretty(bool $isPretty): self
	{
		$this->isPretty = $isPretty;
		return $this;
	}

	/**
	 * @return ElasticHttp
	 */
	public function setGET(): self
	{
		$this->isGet    = true;
		$this->isSearch = false;
		$this->isPost   = false;
		$this->isPut    = false;

		return $this;
	}

	/**
	 * @return ElasticHttp
	 */
	public function setPOST(): self
	{
		$this->isGet    = false;
		$this->isSearch = false;
		$this->isPost   = true;
		$this->isPut    = false;

		return $this;
	}

	/**
	 * @return ElasticHttp
	 */
	public function setDELETE(): self
	{
		$this->isGet    = false;
		$this->isSearch = false;
		$this->isPost   = false;
		$this->isPut    = false;
		$this->isDelete = true;

		return $this;
	}

	/**
	 * @return ElasticHttp
	 */
	public function setPUT(): self
	{
		$this->isGet    = false;
		$this->isSearch = false;
		$this->isPost   = false;
		$this->isPut    = true;

		return $this;
	}

	/**
	 * @return ElasticHttp
	 */
	public function setSearch(): self
	{
		$this->isSearch = true;
		$this->isGet    = false;

		return $this;
	}

	/**
	 * @param array $query
	 * @return ElasticResult
	 * @throws HttpException
	 */
	public function query(array $query): ElasticResult
	{
		$result = $this->doRequest($query);

		if (empty($result)) {
			throw new HttpException('Unable to connect with Elastic Search!');
		}

		return (new ElasticResult($result));
	}

	/**
	 * @param array $query
	 * @return string
	 */
	private function doRequest(array $query): string
	{
		$config      = Config::get('elasticsearch');
		$urlRoot     = $config['schema'] . '://' . $config['host'] . ':' . $config['port'] . '/';
		$this->query = $query;

		$this->buildUri($urlRoot);

		$ch = curl_init($this->url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5 );

		if (is_array($query['body'])) {
			$query['body'] = json_encode($query['body'],  JSON_UNESCAPED_UNICODE);
		}

		if ($this->isPost) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query['body']);
		} else if ($this->isPut) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $query['body']);
		} else if ($this->isDelete) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		return $result;
	}

	/**
	 * @param string $urlRoot
	 * @return bool
	 */
	private function buildUri(string $urlRoot): bool
	{
		if ($this->isSearch) {

			$this->isPost = true;
			$this->url = $urlRoot . $this->query['index'] . '/' . $this->query['type'] . '/_search/?';

		} else if ($this->isGet) {

			$this->url = $urlRoot . $this->query['index'] . '/' . $this->query['type'] . '/' . $this->query['id'];

		} else if ($this->isPut) {

			if (isset($this->query['id'])) {
				$this->url = $urlRoot . $this->query['index'] . '/' . $this->query['type'] . '/' . $this->query['id'];
			} else {
				$this->url = $urlRoot . $this->query['index'];
			}

		} else if ($this->isPost) {

			if (!empty($this->query['type'])) {
				$this->url = $urlRoot . $this->query['index'] . '/' . $this->query['type'] . '/' . $this->query['id'];
			} else {
				$this->url = $urlRoot . (!empty($this->query['index']) ?: $this->query['path']);
			}

		} else if ($this->isDelete) {

			if (isset($this->query['id'])) {
				$this->url = $urlRoot . $this->query['index'] . '/' . $this->query['type'] . '/' . $this->query['id'];
			} else {
				$this->url = $urlRoot . $this->query['index'];
			}
		}

		return true;
	}
}