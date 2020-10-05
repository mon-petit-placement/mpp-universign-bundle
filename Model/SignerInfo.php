<?php

namespace Mpp\UniversignBundle\Model;

class SignerInfo
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var CertificateInfo
     */
    protected $certificateInfo;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var \DateTime
     */
    protected $actionDate;

    /**
     * @var array<int>
     */
    protected $refusedDocs;

    /**
     * @var string
     */
    protected $refusalComment;

    /**
     * @var string
     */
    protected $redirectPolicy;

    /**
     * @var int
     */
    protected $redirectWait;

    /**
     * @param array $options
     *
     * @return self
     */
    public static function createFromArray(array $options): self
    {
        return (new self())
            ->setStatus($options['status'])
            ->setError($options['error'] ?? null)
            ->setCertificateInfo(CertificateInfo::createFromArray($options['certificateInfo']))
            ->setUrl($options['url'])
            ->setId($options['id'])
            ->setEmail($options['email'])
            ->setFirstName($options['firstName'])
            ->setLastName($options['lastName'])
            ->setActionDate($options['actionDate'] ?? null)
            ->setRefusedDocs($options['refusedDocs'] ?? null)
            ->setRefusalComment($options['refusalComment'] ?? null)
            ->setRedirectPolicy($options['redirectPolicy'] ?? null)
            ->setRedirectWait($options['redirectWait'] ?? null)
        ;
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
     * @param string|null $error
     *
     * @return self
     */
    public function setError(?string $error): self
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param CertificateInfo|null $certificateInfo
     *
     * @return self
     */
    public function setCertificateInfo(?CertificateInfo $certificateInfo): self
    {
        $this->certificateInfo = $certificateInfo;

        return $this;
    }

    /**
     * @return CertificateInfo|null
     */
    public function getCertificateInfo(): ?CertificateInfo
    {
        return $this->certificateInfo;
    }

    /**
     * @param string|null $url
     *
     * @return self
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $id
     *
     * @return self
     */
    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $email
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $firstName
     *
     * @return self
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $lastName
     *
     * @return self
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param \DateTime|null $actionDate
     *
     * @return self
     */
    public function setActionDate(?\DateTime $actionDate): self
    {
        $this->actionDate = $actionDate;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getActionDate(): ?\DateTime
    {
        return $this->actionDate;
    }

    /**
     * @param array|null $refusedDocs
     *
     * @return self
     */
    public function setRefusedDocs(?array $refusedDocs): self
    {
        $this->refusedDocs = $refusedDocs;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getRefusedDocs(): ?array
    {
        return $this->refusedDocs;
    }

    /**
     * @param string|null $refusalComment
     *
     * @return self
     */
    public function setRefusalComment(?string $refusalComment): self
    {
        $this->refusalComment = $refusalComment;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRefusalComment(): ?string
    {
        return $this->refusalComment;
    }

    /**
     * @param string|null $redirectPolicy
     *
     * @return self
     */
    public function setRediRectPolicy(?string $redirectPolicy): self
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
