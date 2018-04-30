<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 24.04.2018
 * Time: 15:10
 */

namespace System\Database\DBManager;

interface ObjectMapperInterface
{
    /**
     * @param array $arrayData
     * @param $object object
     * @return mixed
     */
    public function toObject(array $arrayData, $object);

    /**
     * @param $object object
     * @return array
     */
    public function toArray($object): array;
}