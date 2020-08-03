<?php

namespace Mpp\UniversignBundle\XmlRpcClient;

use Behat\Behat\HelperContainer\ContainerInterface;
use Laminas\XmlRpc\Client;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use UniversignRequest\UniversignRequest;


class UniversignXmlRpcClient
{

    /**
     * @var Client
     */
    private $xmlrpcClient;

    /**
     * @var string
     */
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
        // utiliser un singleton
        $this->xmlrpcClient = new Client($this->getURL());
    }


    /**
     * @return string;
     */
    private function getUrl()
    {
        return $this->url;
    }
    /**
     * @return string
     */
    public function initiateConnection()
    {
        $result = $this->xmlrpcClient->call('requester.requestTransaction');

        return $result;
    }

}
