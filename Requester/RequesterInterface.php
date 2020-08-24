<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Model\TransactionRequest;
use Mpp\UniversignBundle\Model\TransactionResponse;

interface RequesterInterface
{
    public function initiateTransactionRequest(): TransactionRequest;
    public function requestTransaction(TransactionRequest $transaction): TransactionResponse;
}