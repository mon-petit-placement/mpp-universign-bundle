<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Model\Transaction;
use Mpp\UniversignBundle\Model\TransactionResponse;

interface RequesterInterface
{
    public function initiateTransaction(): Transaction;
    public function requestTransaction(Transaction $transaction): TransactionResponse;
}