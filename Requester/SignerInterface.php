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
    public function initiateTransactionRequest(array $options = []): TransactionRequest;

    public function requestTransaction(TransactionRequest $transactionRequest): TransactionResponse;

    /**
     * @return array<Document>
     */
    public function getDocuments(string $transactionId): array;

    /**
     * @return array<Document>
     */
    public function getDocumentsByCustomId(string $customId): array;

    public function getTransactionInfo(string $transactionId): TransactionInfo;

    public function getTransactionInfoByCustomId(string $customId): TransactionInfo;


    public function relaunchTransaction(string $transactionId): TransactionInfo;

    public function cancelTransaction(string $transactionId): TransactionInfo;

    public function sign(Base64 $document): ?string;

    public function signWithOptions(Base64 $document, SignOptions $options): ?string;
}
