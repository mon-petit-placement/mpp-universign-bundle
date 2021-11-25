<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Exception\FaultException;
use PhpXmlRpc\Client;
use PhpXmlRpc\Encoder;
use PhpXmlRpc\Request;

abstract class XmlRpcRequester
{
    protected Client $xmlRpcClient;

    public function __construct(?array $clientOptions = null)
    {
        $this->xmlRpcClient = new Client($this->getURL());
        $this->xmlRpcClient->setCurlOptions($clientOptions);
    }

    abstract public function getUrl(): string;

    /**
     * @param string $method
     * @param $args
     * @return mixed Note that the client will always return a Response object, even if the call fails
     * @throws FaultException
     */
    protected function call(string $method, $args)
    {
        $encoder = new Encoder();
        $response = $this->xmlRpcClient->send(new Request($method, $encoder->encode($args)));

        if (0 != $response->faultCode()) {
            throw new FaultException($method, $response);
        }

        return $encoder->decode($response->value());
    }
}
