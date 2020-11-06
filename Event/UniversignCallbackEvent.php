<?php

namespace Mpp\UniversignBundle\Event;

class UniversignCallbackEvent
{
    const STATUS_READY = 0;
    const STATUS_EXPIRED = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELED = 3;
    const STATUS_ERROR = 4;
    /**
     * All signatories have signed BUT waiting for Universign registration authority validation.
     */
    const STATUS_SIGNED = 5;

    /**
     * @var string
     */
    private $transactionId;

    /**
     * @var int
     */
    private $indexSigner;

    /**
     * @var int
     */
    private $status;

    /**
     * @param string $transactionId
     * @param int $indexSigner
     * @param int $status
     */
    public function __construct(string $transactionId, int $indexSigner, int $status)
    {
        $this->transactionId = $transactionId;
        $this->indexSigner = $indexSigner;
        $this->status = $status;
    }

    /**
     * @retrun string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return int
     */
    public function getIndexSigner(): int
    {
        return $this->indexSigner;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
