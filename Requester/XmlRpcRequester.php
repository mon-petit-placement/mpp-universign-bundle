<?php

namespace Mpp\UniversignBundle\Requester;

use Laminas\XmlRpc\Client;
use Mpp\UniversignBundle\Model\Transaction;
use Mpp\UniversignBundle\Model\TransactionResponse;

class XmlRpcRequester implements RequesterInterface
{

    /**
     * @var string
     */
    protected $url;

    /**
     * @var Client
     */
    protected $xmlRpcClient;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->xmlRpcClient = new Client($this->getURL());
    }

    /**
     * @return string;
     */
    private function getUrl()
    {
        return $this->url;
    }


    /**
     * @return Transaction
     */
    public function initiateTransaction(): Transaction
    {
        return new Transaction();
    }

    /**
     * @param Transaction $transaction
     *
     * @return TransactionResponse
     */
    public function requestTransaction(Transaction $transaction): TransactionResponse
    {
        $response = $this->xmlRpcClient->call('requester.requestTransaction', [$transaction->toArray()]);
        dd($response);
        $transactionResponse = new TransactionResponse();
        //transform the obj Transaction to a array
        $arrayTransaction = (array)$transaction;
        dump($arrayTransaction);
        return $transactionResponse;
    }
}
