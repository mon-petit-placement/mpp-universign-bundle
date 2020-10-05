<?php

namespace Mpp\UniversignBundle\Event;

class UniversignCallbackEvent
{
    const VALID = 'VALID';
    const INVALID = 'INVALID';
    const TRANSACTION_VALID = 'mpp_universign.callback.valid';
    const TRANSACTION_INVALID = 'mpp_universign.callback.invalid';

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @param string $transactionId
     */
    public function __construct(string $transactionId)
    {
        $this->transctionId = $transactionId;
    }

    /**
     * @retrun string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
