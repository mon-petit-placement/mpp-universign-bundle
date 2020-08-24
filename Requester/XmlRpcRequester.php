<?php

namespace Mpp\UniversignBundle\Requester;

use \Mpp\UniversignBundle\Model\Document;
use Laminas\XmlRpc\Client;
use Mpp\UniversignBundle\Model\TransactionRequest;
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
            if (true === $skipNullValue && (null === $value || empty($value) && 0 !== $value)) {
                continue;
            }
            $data[$property->getName()] = self::flatten($value, $skipNullValue);
        }

        return $data;
    }

    /**
     * @return TransactionRequest
     */
    public function initiateTransactionRequest(): TransactionRequest
    {
        return new TransactionRequest();
    }

    /**
     * @param TransactionRequest $transactionRequest
     *
     * @return TransactionResponse
     */
    public function requestTransaction(TransactionRequest $transactionRequest): TransactionResponse
    {
        $flattenedTransactionRequest = self::flatten($transactionRequest);
        $flattenedTransactionRequest['documents'] = array_values($flattenedTransactionRequest['documents']);
        $response = $this->xmlRpcClient->call('requester.requestTransaction', [$flattenedTransactionRequest]);

        return new TransactionResponse($response['id'], $response['url']);
    }

    /**
     * @param string $documentId
     *
     * @return array<Document>
     */
    public function RequestDocument(string $documentId): array
    {
        $documents  =  $this->xmlRpcClient->call('requester.getDocuments', $documentId);
        $documentsReponse = [];
        foreach ($documents as $document) {
            # code...
            $documentsResponse[] = Document::createFromArray($document);
        }

        return $documentsResponse;
    }

    /**
     * @param string $documentCustomId
     *
     * @return array<Document>
     */
    public function RequestDocumentByCustomId(string $documentCustomId): array
    {
        $documents  =  $this->xmlRpcClient->call('requester.getDocumentsByCustomId', $documentCustomId);
        $documentsReponse = [];
        foreach ($documents as $document) {
            # code...
            $documentsResponse[] = Document::createFromArray($document);
        }

        return $documentsResponse;
    }
}
