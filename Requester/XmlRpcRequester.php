<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Model\Document;
use Laminas\XmlRpc\Client;
use Psr\Log\LoggerInterface;
use Laminas\XmlRpc\Client\Exception\FaultException;
use Mpp\UniversignBundle\Model\TransactionRequest;
use Mpp\UniversignBundle\Model\TransactionResponse;

class XmlRpcRequester implements RequesterInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var Client
     */
    protected $xmlRpcClient;

    public function __construct(LoggerInterface $logger, string $url)
    {
        $this->logger = $logger;
        $this->url = $url;
        $this->xmlRpcClient = new Client($this->getURL());
    }

    /**
     * @return string;
     */
    public function getUrl()
    {
        return $this->url;
    }

    public static function flatten($data, bool $skipNullValue = true)
    {
        $flattenedData = [];

        if (is_object($data) &&
            !($data instanceof \Laminas\XmlRpc\Value\DateTime) &&
            !($data instanceof \Laminas\XmlRpc\Value\Base64)
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

    /**
     * {@inheritDoc}
     */
    public function initiateTransactionRequest(): TransactionRequest
    {
        return new TransactionRequest();
    }

    /**
     * {@inheritDoc}
     */
    public function requestTransaction(TransactionRequest $transactionRequest): TransactionResponse
    {
        $transactionResponse = new TransactionResponse();

        $flattenedTransactionRequest = self::flatten($transactionRequest);
        $flattenedTransactionRequest['documents'] = array_values($flattenedTransactionRequest['documents']);

        try {
            $response = $this->xmlRpcClient->call('requester.requestTransaction', [$flattenedTransactionRequest]);
            $this->logger->info('[Universign - requester.requestTransaction] SUCCESS');
            $transactionResponse
                ->setId($response['id'])
                ->setUrl($response['url'])
                ->setState(TransactionResponse::STATE_SUCCESS)
            ;
        } catch (FaultException $fe) {
            $transactionResponse
                ->setState(TransactionResponse::STATE_ERROR)
                ->setErrorMessage($fe->getMessage());
            ;
            $this->logger->error(sprintf(
                '[Universign - requester.requestTransaction] ERROR: %s',
                $fe->getMessage()
            ));
        }

        return $transactionResponse;
    }

    /**
     * {@inheritDoc}
     */
    public function getDocuments(string $documentId): array
    {
        return $this->xmlRpcClient->call('requester.getDocuments', $documentId);
    }

    /**
     * {@inheritDoc}
     */
    public function getDocumentsByCustomId(string $customId): array
    {
        return  $this->xmlRpcClient->call('requester.getDocumentsByCustomId', $customId);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionInfo(string $transactionId): array
    {
        return $this->xmlRpcClient->call('requester.getTransactionInfo', $transactionId);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionInfoByCustomId(string $customId): array
    {
        return $this->xmlRpcClient->call('requester.getTransactionInfoByCustomId', $customId);
    }

    /**
     * {@inheritDoc}
     */
    public function relaunchTransaction(string $transactionId): void
    {
        $this->xmlRpcClient->call('requester.relaunchTransaction', $transactionId);
    }

     /**
     * {@inheritDoc}
     */
    public function cancelTransaction(string $transactionId): void
    {
        $this->xmlRpcClient->call('requester.cancelTransaction', $transactionId);
    }

}
