<?php

namespace Mpp\UniversignBundle\Requester;

use Laminas\XmlRpc\Client;
use Laminas\XmlRpc\Client\Exception\FaultException;
use Mpp\UniversignBundle\Model\Document;
use Mpp\UniversignBundle\Model\InitiatorInfo;
use Mpp\UniversignBundle\Model\SignerInfo;
use Mpp\UniversignBundle\Model\TransactionInfo;
use Mpp\UniversignBundle\Model\TransactionRequest;
use Mpp\UniversignBundle\Model\TransactionResponse;
use Psr\Log\LoggerInterface;

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

    /**
     * @param mixed $data
     * @param bool $skipNullValue
     *
     * @return array
     */
    public static function flatten($data, bool $skipNullValue = true): array
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
        $documents = [];

        try {
            $documents = $this->xmlRpcClient->call('requester.getDocuments', $documentId);
            $this->logger->info('[Universign - requester.getDocuments] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf(
                '[Universign - requester.getDocuments] ERROR: %s',
                $fe->getMessage()
            ));
        }

        return $documents;
    }

    /**
     * {@inheritDoc}
     */
    public function getDocumentsByCustomId(string $customId): array
    {
        $documents = [];

        try {
            $documents = $this->xmlRpcClient->call('requester.getDocumentsByCustomId', $customId);
            $this->logger->info('[Universign - requester.getDocumentsByCustomId] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf(
                '[Universign - requester.getDocumentsByCustomId] ERROR: %s',
                $fe->getMessage()
            ));
        }
        return $documents;

    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionInfo(string $transactionId): TransactionInfo
    {
        $transactionInfo = new TransactionInfo();

        try {
            $response = $this->xmlRpcClient->call('requester.getTransactionInfo', $transactionId);
            $this->logger->info('[Universign - requester.getTransactionInfo] SUCCESS');
            $transactionInfo
                ->setState(TransactionInfo::STATE_SUCCESS)
                ->setStatus($response['status'] ?? null)
                ->setCurrentSigner($response['currentSigner'] ?? null)
                ->setCreationDate(\DateTime::createFromFormat('Ymd\TH:i:s', $response['creationDate']) ?? null)
                ->setDescription($response['description'] ?? null)
                ->setInitiatorInfo(InitiatorInfo::createFromArray($response['initiatorInfo']) ?? null)
                ->setEachField($response['eachField'] ?? null)
                ->setCustomerId($response['customerId'] ?? null)
                ->setTransactionId($response['transactionId'] ?? null)
                ->setRedirectPolicy($response['redirectPolicy'] ?? null)
                ->setRedirectWait($response['redirectWait'] ?? null)
            ;
            foreach ($response['signerInfos'] as $signerInfo) {
                $transactionInfo->addSignerInfo(SignerInfo::createFromArray($signerInfo));
            }

        } catch (FaultException $fe) {
            $this->logger->error(sprintf(
                '[Universign - requester.getTransactionInfo] ERROR: %s',
                $fe->getMessage()
            ));
            $transactionInfo
                ->setState(TransactionInfo::STATE_ERROR)
                ->setErrorMessage($fe->getMessage())
            ;
        }

        return $transactionInfo;
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactionInfoByCustomId(string $customId): TransactionInfo
    {
        $transactionInfoResponse = new TransactionInfo();

        try {
            $response = $this->xmlRpcClient->call('requester.getTransactionInfoByCustomId', $customId);
            $this->logger->info('[Universign - requester.getTransactionInfoByCustomId] SUCCESS');
            $transactionInfoResponse
                ->setState(TransactionInfo::STATE_SUCCESS)
                ->setStatus($response['status'])
                ->setCurrentSigner($response['currentSigner'])
                ->setCreationDate(\DateTime::createFromFormat('Ymd\TH:i:s', $response['creationDate']))
                ->setDescription($response['description'])
                ->setInitiatorInfo(InitiatorInfo::createFromArray($response['initiatorInfo']))
                ->setEachField($response['eachField'])
                ->setCustomerId($response['customerId'])
                ->setTransactionId($response['transactionId'])
                ->setRedirectPolicy($response['redirectPolicy'])
                ->setRedirectWait($response['redirectWait'])
            ;
        } catch (FaultException $fe) {
            $this->logger->error(sprintf(
                '[Universign - requester.getTransactionInfoByCustomId] ERROR: %s',
                $fe->getMessage()
            ));
            $transactionInfoResponse
                ->setState(TransactionInfo::STATE_ERROR)
                ->setErrorMessage($fe->getMessage());
        }

        return $transactionInfoResponse;
    }

    /**
     * {@inheritDoc}
     */
    public function relaunchTransaction(string $transactionId): void
    {
        try {
            $this->xmlRpcClient->call('requester.relaunchTransaction', $transactionId);
            $this->logger->info('[Universign - requester.relaunchTransaction] SUCCESS');

        } catch (FaultException $fe) {
            $this->logger->error(sprintf(
                '[Universign - requester.relaunchTransaction] ERROR: %s',
                $fe->getMessage()
            ));
        }
    }

     /**
     * {@inheritDoc}
     */
    public function cancelTransaction(string $transactionId): void
    {
        try {
            $this->xmlRpcClient->call('requester.cancelTransaction', $transactionId);
            $this->logger->info('[Universign - requester.cancelTransaction] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf(
                '[Universign - requester.cancelTransaction] ERROR: %s',
                $fe->getMessage()
            ));
        }
    }
}
