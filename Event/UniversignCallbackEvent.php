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

    private string $transactionId;

    private ?int $indexSigner;

    private int $status;

    public function __construct(string $transactionId, ?int $indexSigner, int $status)
    {
        $this->transactionId = $transactionId;
        $this->indexSigner = $indexSigner;
        $this->status = $status;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getIndexSigner(): ?int
    {
        return $this->indexSigner;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
