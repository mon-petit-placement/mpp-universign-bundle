<?php

namespace Mpp\UniversignBundle\Requester;

use Laminas\XmlRpc\Client;
use Laminas\XmlRpc\Value\Base64;
use Laminas\XmlRpc\Value\DateTime;

abstract class XmlRpcRequester
{
    /**
     * @var Client
     */
    protected $xmlRpcClient;

    public function __construct()
    {
        $this->xmlRpcClient = new Client($this->getURL());
    }

    /**
     * @return string;
     */
    abstract public function getUrl();

    /**
     * @param mixed $data
     * @param bool $skipNullValue
     *
     * @return mixed
     */
    public static function flatten($data, bool $skipNullValue = true)
    {
        $flattenedData = [];

        if (is_object($data) &&
            !($data instanceof DateTime) &&
            !($data instanceof Base64)
        ) {
            return self::dismount($data, $skipNullValue);
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $flattenedValue = self::flatten($value, $skipNullValue);
                if (true === $skipNullValue && null === $flattenedValue) {
                    continue;
                }
                $flattenedData[$key] = $flattenedValue;
            }

            return $flattenedData;
        }

        return $data;
    }

    /**
     * @param mixed $object
     * @param bool $skipNullValue
     *
     * @return array
     */
    public static function dismount($object, bool $skipNullValue = true): array
    {
        $rc = new \ReflectionClass($object);
        $data = [];
        foreach ($rc->getProperties() as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            $property->setAccessible(false);
            if (true === $skipNullValue && (null === $value || (is_array($value) && empty($value)))) {
                continue;
            }
            $data[$property->getName()] = self::flatten($value, $skipNullValue);
        }

        return $data;
    }
}
