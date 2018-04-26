<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.03.2018
 * Time: 20:14
 */

namespace ElasticSearch;

use Traits\SingletonTrait;

class ElasticSearch implements ElasticSearchInterface
{
	use SingletonTrait;

	/**
	 * @var ElasticQuery
	 */
	private $strategy;

	/**
	 * @return ElasticQuery
	 */
	private function getStrategy(): ElasticQuery
	{
		if (!$this->strategy instanceof ElasticQuery) {
			$this->strategy = new ElasticQuery(new ElasticHttp());
		}

		return $this->strategy;
	}

	/**
	 * @param string $method
	 * @param $body
	 * @return ElasticSearch
	 */
	public function setBody(string $method, $body): self
	{
		$this->getStrategy()->setBody($method, $body);
		return $this;
	}

	/**
	 * @param string $index
	 * @return ElasticSearch
	 */
	public function setIndex(string $index): self
	{
		$this->getStrategy()->setIndex($index);
		return $this;
	}

	/**
	 * @param string $type
	 * @return ElasticSearch
	 */
	public function setType(string $type): self
	{
		$this->getStrategy()->setType($type);
		return $this;
	}

	/**
	 * @param string $id
	 * @return ElasticSearch
	 */
	public function setId(string $id): self
	{
		$this->getStrategy()->setType($id);
		return $this;
	}

	/**
	 * @param string $path
	 * @return ElasticSearch
	 */
	public function setPath(string $path): self
	{
		$this->getStrategy()->setPath($path);
		return $this;
	}

	/**
	 * @param array $body
	 * @return ElasticResult
	 */
	public function search(array $body): ElasticResult
	{
		return $this->getStrategy()->search($body);
	}

	/**
	 * @return ElasticResult
	 */
	public function createIndex(): ElasticResult
	{
		return $this->getStrategy()->createIndex();
	}

	/**
	 * @return ElasticResult
	 */
	public function deleteIndex(): ElasticResult
	{
		return $this->getStrategy()->deleteIndex();
	}

	/**
	 * @return void
	 */
	public function update()
	{
		$this->getStrategy()->update();
	}

	/**
	 * @param string $id
	 * @return ElasticResult
	 */
	public function delete(string $id): ElasticResult
	{
		return $this->getStrategy()->delete($id);
	}

	/**
	 * @param array $body
	 * @return ElasticResult
	 */
	public function add(array $body): ElasticResult
	{
		return $this->getStrategy()->add($body);
	}

	/**
	 * @param string $id
	 * @return ElasticResult
	 */
	public function get(string $id = ''): ElasticResult
	{
		return $this->getStrategy()->get($id);
	}

	/**
	 * @return ElasticResult
	 */
	public function execute(): ElasticResult
	{
		return $this->getStrategy()->execute();
	}

}