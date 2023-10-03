<?php

namespace Mpp\UniversignBundle\Model;

class TransactionResponse
{
    public const STATE_SUCCESS = 'SUCCESS';
    public const STATE_ERROR = 'ERROR';

    protected ?string $id;

    protected ?string $url;

    protected ?string $state;

    protected ?string $errorCode;

    protected ?string $errorMessage;

    public function __construct()
    {
        $this->id = null;
        $this->url = null;
        $this->state = null;
        $this->errorCode = null;
        $this->errorMessage = null;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
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

    public function setErrorCode(?string $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
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
}
