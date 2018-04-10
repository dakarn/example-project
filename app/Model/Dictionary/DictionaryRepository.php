<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.03.2018
 * Time: 18:30
 */

namespace Model\Dictionary;

class DictionaryRepository
{
	/** @var  DictionaryStorage */
	private $storage;

	private $dictList = [];

	public function getDictionaryStorage(): DictionaryStorage
	{
		if (!$this->storage instanceof DictionaryStorage) {
			$this->storage = new DictionaryStorage();
		}

		return $this->storage;
	}

	public function getAllDictionaries(): array
	{
		$results = $this->getDictionaryStorage()
			->getDictionaries();

		foreach ($results as $result) {
			$this->dictList[$result['id']] = new Dictionary($result);
		}

		return $this->dictList;
	}

	public function getDictionaryById(int $id): Dictionary
	{
		$result = $this->getDictionaryStorage()
			->getDictionaryById($id);

		if (empty($result)) {
			throw new \Exception('Dictionary with ' . $id . ' not found!');
		}

		return new Dictionary($result);

	}

	public function searchWord(array $data)
	{
		$dictionary = new Dictionary($data);

		$this->getDictionaryStorage()
			->searchDictionaryByText($dictionary);
	}

	public function addWord(array $data): bool
	{
		$dictionary = new Dictionary($data);

		$result = $this->getDictionaryStorage()
			->getDictionaryByText($dictionary);

		if (!empty($result)) {
			return false;
		}

		$this->getDictionaryStorage()
			->addWord($dictionary);

		return true;
	}

	public function deleteWord(int $id): bool
	{
		$this->getDictionaryStorage()
			->deleteWord($id);

		return true;
	}
}