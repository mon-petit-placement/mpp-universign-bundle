<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionInfo
{
    const STATE_SUCCESS = "SUCCESS";
    const STATE_ERROR = "ERROR";

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var array<SignerInfo>
     */
    protected $signerInfos;

    /**
     * @var int
     */
    protected $currentSigner;

    /**
     * @var \DateTime
     */
    protected $creationDate;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var InitiatorInfo
     */
    protected $initiatorInfo;

    /**
     * @var bool
     */
    protected $eachField;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var string
     */
    protected $transactionId;

    /**
     * @var string
     */
    protected $redirectPolicy;

    /**
     * @var int
     */
    protected $redirectWait;

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
    }

    /**
     * @param string|null $state
     *
     * @return self
     */
    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $errorMessage
     *
     * @return self
     */
    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param string|null $status
     *
     * @return self
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param array|null $signerInfos
     *
     * @return self
     */
    public function setSignerInfos(?array $signerInfos): self
    {
        $this->signerInfos = $signerInfos;

        return $this;
    }

    /**
     * @param SignerInfo|null $signerInfo
     *
     * @return self
     */
    public function addSignerInfo(?SignerInfo $signerInfo): self
    {
        $this->signerInfos[] = $signerInfo;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getSignerInfos(): ?array
    {
        return $this->signerInfos;
    }

    /**
     * @param int|null $currentSigner
     *
     * @return self
     */
    public function setCurrentSigner(?int $currentSigner): self
    {
        $this->currentSigner = $currentSigner;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCurrentSigner(): ?int
    {
        return $this->currentSigner;
    }

    /**
     * @param \DateTime|null $creationDate
     *
     * @return self
     */
    public function setCreationDate(?\DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param string|null $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param InitiatorInfo|null $initiatorInfo
     *
     * @return self
     */
    public function setInitiatorInfo(?InitiatorInfo $initiatorInfo): self
    {
        $this->initiatorInfo = $initiatorInfo;

        return $this;
    }

    /**
     * @return InitiatorInfo|null
     */
    public function getInitiatorInfo(): ?InitiatorInfo
    {
        return $this->initiatorInfo;
    }

    /**
     * @param bool|null $eachField
     *
     * @return self
     */
    public function setEachField(?bool $eachField): self
    {
        $this->eachField = $eachField;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getEachField(): ?bool
    {
        return $this->eachField;
    }

    /**
     * @param string|null $customerId
     *
     * @return self
     */
    public function setCustomerId(?string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    /**
     * @param string|null $transactionId
     *
     * @return self
     */
    public function setTransactionId(?string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    /**
     * @param string|null $redirectPolicy
     *
     * @return self
     */
    public function setRedirectPolicy(?string $redirectPolicy): self
    {
        $this->redirectPolicy = $redirectPolicy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectPolicy(): ?string
    {
        return $this->redirectPolicy;
    }

    /**
     * @param int|null $redirectWait
     *
     * @return self
     */
    public function setRedirectWait(?int $redirectWait): self
    {
        $this->redirectWait = $redirectWait;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRedirectWait(): ?int
    {
        return $this->redirectWait;
    }
}
