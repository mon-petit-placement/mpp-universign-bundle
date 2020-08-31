<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Model\Document;
use Mpp\UniversignBundle\Model\TransactionInfo;
use Mpp\UniversignBundle\Model\TransactionRequest;
use Mpp\UniversignBundle\Model\TransactionResponse;

interface RequesterInterface
{
    /**
     * @return TransactionRequest
     */
    public function initiateTransactionRequest(): TransactionRequest;

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
     */
    public function relaunchTransaction(string $transactionId): void;

    /**
     * @param string $transactionId
     */
    public function cancelTransaction(string $transactionId): void;
}
