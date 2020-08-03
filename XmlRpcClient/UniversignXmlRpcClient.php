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
     * @var ContainerBuilder
     */
    private $container;

    public function __construct()
    {
        $this->xmlrpcClient = new Client($this->getURL());
        // $this->container = $container;
    }

    /**
     * @return string;
     */
    private function getURL()
    {
        $scheme = $_ENV['UNIVERSIGN_SCHEME'];
        $user = $_ENV['UNIVERSIGN_USER_EMAIL'];
        $password = $_ENV['UNIVERISGN_USER_PASSWORD'];
        $host = $_ENV['UNIVERISGN_HOST'];
        $url_path = $_ENV['UNIVERSIGN_URL_PATH'];
        // $user = $this->container->getParameter('user_email');
        // $password = $this->container->getParameter('user_password');
        // $host = $this->container->getParameter('host');
        // $url_path = $this->container->getParameter('url_path');

        return sprintf(
            '%s://%s:%s@ws.%s/%s',
            $scheme,
            $user,
            $password,
            $host,
            $url_path
        );
    }
    /**
     * @return string
     */
    public function initiateConnection()
    {
        $result = $this->xmlrpcClient->call(UniversignRequest::requestTransaction);

        return $result;
    }

}
