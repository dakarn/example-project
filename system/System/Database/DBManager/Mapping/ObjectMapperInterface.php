<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 24.04.2018
 * Time: 15:10
 */

namespace System\Database\DBManager\Mapping;

interface ObjectMapperInterface
{
	/**
	 * @param array $arrayData
	 * @param string $objectInput
	 * @return mixed
	 */
    public function toObject(array $arrayData, string $objectInput);

    /**
     * @param $object object
     * @return array
     */
    public function toArray($object): array;
}