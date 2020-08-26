<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Model\Document;
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
     * @return array
     */
    public function getTransactionInfo(string $transactionId): array;

    /**
     * @param string $customId
     *
     * @return array
     */
    public function getTransactionInfoByCustomId(string $customId): array;

    /**
     * @param string $transactionId
     *
     * @return void
     */
    public function relaunchTransaction(string $transactionId): void;

    /**
     * @param string $transactionId
     *
     * @return void
     */
    public function cancelTransaction(string $transactionId): void;
}