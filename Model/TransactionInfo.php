<?php

namespace Mpp\UniversignBundle\Model;

class TransactionInfo
{
    public const STATE_SUCCESS = 'SUCCESS';
    public const STATE_ERROR = 'ERROR';

    public const STATUS_READY = 'ready';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_FAILED = 'failed';
    public const STATUS_COMPLETED = 'completed';

    protected ?string $state;

    protected ?string $errorMessage;

    protected int $errorCode;

    protected ?string $status;

    /**
     * @var array<SignerInfo>
     */
    protected array $signerInfos;

    protected ?int $currentSigner;

    protected ?\DateTime $creationDate;

    protected ?string $description;

    protected ?InitiatorInfo $initiatorInfo;

    protected ?bool $eachField;

    protected ?string $customerId;

    protected ?string $transactionId;

    protected ?string $redirectPolicy;

    protected ?int $redirectWait;

    public function __construct()
    {
        $this->state = null;
        $this->errorMessage = null;
        $this->status = null;
        $this->signerInfos = [];
        $this->currentSigner = null;
        $this->creationDate = null;
        $this->description = null;
        $this->initiatorInfo = null;
        $this->eachField = null;
        $this->customerId = null;
        $this->transactionId = null;
        $this->redirectPolicy = null;
        $this->redirectWait = null;
        $this->errorCode = 0;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param array<SignerInfo> $signerInfos
     */
    public function setSignerInfos(array $signerInfos): self
    {
        $this->signerInfos = $signerInfos;

        return $this;
    }

    public function addSignerInfo(SignerInfo $signerInfo): self
    {
        $this->signerInfos[] = $signerInfo;

        return $this;
    }

    /**
     * @return array<SignerInfo>
     */
    public function getSignerInfos(): array
    {
        return $this->signerInfos;
    }

    public function setCurrentSigner(?int $currentSigner): self
    {
        $this->currentSigner = $currentSigner;

        return $this;
    }

    public function getCurrentSigner(): ?int
    {
        return $this->currentSigner;
    }

    public function setCreationDate(?\DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setInitiatorInfo(?InitiatorInfo $initiatorInfo): self
    {
        $this->initiatorInfo = $initiatorInfo;

        return $this;
    }

    public function getInitiatorInfo(): ?InitiatorInfo
    {
        return $this->initiatorInfo;
    }

    public function setEachField(?bool $eachField): self
    {
        $this->eachField = $eachField;

        return $this;
    }

    public function getEachField(): ?bool
    {
        return $this->eachField;
    }

    public function setCustomerId(?string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setTransactionId(?string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setRedirectPolicy(?string $redirectPolicy): self
    {
        $this->redirectPolicy = $redirectPolicy;

        return $this;
    }

    public function getRedirectPolicy(): ?string
    {
        return $this->redirectPolicy;
    }

    public function setRedirectWait(?int $redirectWait): self
    {
        $this->redirectWait = $redirectWait;

        return $this;
    }

    public function getRedirectWait(): ?int
    {
        return $this->redirectWait;
    }
}
