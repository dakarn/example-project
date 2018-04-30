<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.04.2018
 * Time: 20:07
 */

namespace System\Database\DBManager;

use Psr\Log\InvalidArgumentException;
use Traits\SingletonTrait;

class ObjectMapper implements ObjectMapperInterface
{
    use SingletonTrait;

    /**
     * @var string
     */
    const SETTER = 'set';

    /**
     * @var string
     */
    const GETTER = 'get';

    /**
     * @param object $object
     * @return array
     */
    public function toArray($object): array
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('');
        }

        $response = [];
        $methods  = get_class_methods($object);

        foreach ($methods as $indexMethod => $getMethodName) {
            if (substr($getMethodName, 0, 3) === self::GETTER) {
                $property            = lcfirst(substr($getMethodName, 3));
                $response[$property] = $object->$getMethodName();
            }
        }

       return $response;
    }

    /**
     * @param array $arrayData
     * @param object $object
     * @return object
     */
    public function toObject(array $arrayData, $object)
    {
        if (!is_array($arrayData) || empty($arrayData)) {
            throw new InvalidArgumentException('');
        }

        foreach ($arrayData as $property => $itemValue) {

            $setMethodName = self::SETTER . ucfirst($property);

            if (method_exists($object, $setMethodName)) {
                $object->$setMethodName($itemValue);
            }
        }

        return $object;
    }
}