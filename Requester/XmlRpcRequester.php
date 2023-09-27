<?php

namespace Mpp\UniversignBundle\Requester;

use PhpXmlRpc\Client;
use PhpXmlRpc\Encoder;
use PhpXmlRpc\Helper\XMLParser;
use PhpXmlRpc\Request;
use PhpXmlRpc\Value;

abstract class XmlRpcRequester
{
    /**
     * @var Client
     */
    protected $xmlRpcClient;

    /**
    * @var Encoder
    */
    protected $encoder;


    public function __construct(Encoder $encoder, array $clientOptions = [])
    {
        $this->encoder = $encoder;
        $this->xmlRpcClient = new Client($this->getUrl());
        foreach ($clientOptions as $name => $value) {
            $this->xmlRpcClient->setOption($name, $value);
        }
        $this->xmlRpcClient->setOption(Client::OPT_RETURN_TYPE, XMLParser::RETURN_PHP);
    }

    /**
     * @return string;
     */
    abstract public function getUrl();

    /**
     * @param mixed $data
     * @param bool $skipNullValue
     *
     * @return Value|array<string, Value>
     */
    public function flatten($data, bool $skipNullValue = true)
    {
        $flattenedData = [];

        if (is_object($data) && !$data instanceof Value && !$data instanceof \DateTime) {
            return $this->dismount($data, $skipNullValue);
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $flattenedValue = $this->flatten($value, $skipNullValue);
                if (true === $skipNullValue && null === $flattenedValue) {
                    continue;
                }
                if (!$flattenedValue instanceof Value) {
                    $flattenedValue = $this->encoder->encode($flattenedValue);
                }
                $flattenedData[$key] = $flattenedValue;
            }

            return $flattenedData;
        }

        if (!$data instanceof Value) {
            $data = $this->encoder->encode($data);
        }

        return $data;
    }

    /**
     * @param mixed $object
     * @param bool $skipNullValue
     *
     * @return array
     */
    public function dismount($object, bool $skipNullValue = true): array
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
            $data[$property->getName()] = $this->flatten($value, $skipNullValue);
        }

        return $data;
    }

    protected function send(string $method, $params): mixed
    {
        $response = $this->xmlRpcClient->send($this::buildRequest($method, $this->flatten($params)));

        return $response->value();
    }

    protected static function buildRequest(string $method, $params)
    {
        if (!is_array($params)) {
            $params = [$params];
        }

        return new Request(
            $method,
            $params
        );
    }
}
