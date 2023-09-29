<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Model\Document;
use Mpp\UniversignBundle\Model\SignOptions;
use Mpp\UniversignBundle\Model\TransactionInfo;
use Mpp\UniversignBundle\Model\TransactionRequest;
use Mpp\UniversignBundle\Model\TransactionResponse;
use Mpp\UniversignBundle\Model\XmlRpc\Base64;

interface SignerInterface
{
    /**
     * @param array $options
     *
     * @return TransactionRequest
     */
    public function initiateTransactionRequest(array $options = []): TransactionRequest;

    /**
     * @param TransactionRequest $transactionRequest
     *
     * @return TransactionResponse
     */
    public function requestTransaction(TransactionRequest $transactionRequest): TransactionResponse;

    /**
     * @param string $transactionId
     *
     * @return array<Document>
     */
    public function getDocuments(string $transactionId): array;

    /**
     * @param string $customId
     *
     * @return array<Document>
     */
    public function getDocumentsByCustomId(string $customId): array;

    /**
     * @param string $transactionId
     *
     * @return TransactionInfo
     */
    public function getTransactionInfo(string $transactionId): TransactionInfo;

    /**
     * @param string $customId
     *
     * @return TransactionInfo
     */
    public function getTransactionInfoByCustomId(string $customId): TransactionInfo;

    /**
     * @param string $transactionId
     *
     * @return TransactionInfo
     */
    public function relaunchTransaction(string $transactionId): TransactionInfo;

    /**
     * @param string $transactionId
     *
     * @return TransactionInfo
     */
    public function cancelTransaction(string $transactionId): TransactionInfo;

    /**
     * @param Base64 $document
     *
     * @return string|null
     */
    public function sign(Base64 $document): ?string;

    /**
     * @param Base64 $document
     * @param SignOptions $options
     *
     * @return string|null
     */
    public function signWithOptions(Base64 $document, SignOptions $options): ?string;
}
