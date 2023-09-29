<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignerInfo
{
    public const STATUS_WAITING = 'waiting';
    public const STATUS_READY = 'ready';
    public const STATUS_ACCESSED = 'accessed';
    public const STATUS_CODE_SENT = 'code-sent';
    public const STATUS_SIGNED = 'signed';
    public const STATUS_PENDING_ID_DOCS = 'pending-id-docs';
    public const STATUS_PENDING_VALIDATION = 'pending-validation';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_FAILED = 'failed';

    protected ?string $status;

    protected ?string $error;

    protected ?CertificateInfo $certificateInfo;

    protected ?string $url;

    protected ?string $id;

    protected ?string $email;

    protected ?string $firstName;

    protected ?string $lastName;

    protected ?\DateTime $actionDate;

    /**
     * @var array<int>
     */
    protected array $refusedDocs;

    protected ?string $refusalComment;

    protected ?string $redirectPolicy;

    protected ?int $redirectWait;

    protected ?array $idDocuments;

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('status', null)->setAllowedTypes('status', ['null', 'string'])
            ->setDefault('error', null)->setAllowedTypes('error', ['null', 'string'])
            ->setDefault('certificateInfo', null)->setAllowedTypes('certificateInfo', ['null', 'array', CertificateInfo::class])->setNormalizer('certificateInfo', function (Options $options, $value) {
                if (null === $value || $value instanceof CertificateInfo) {
                    return $value;
                }

                return CertificateInfo::createFromArray($value);
            })
            ->setDefault('url', null)->setAllowedTypes('url', ['null', 'string'])
            ->setDefault('id', null)->setAllowedTypes('id', ['null', 'string'])
            ->setDefault('email', null)->setAllowedTypes('email', ['null', 'string'])
            ->setDefault('firstName', null)->setAllowedTypes('firstName', ['null', 'string'])
            ->setDefault('lastName', null)->setAllowedTypes('lastName', ['null', 'string'])
            ->setDefault('actionDate', null)->setAllowedTypes('actionDate', ['null', 'string', \DateTime::class])->setNormalizer('actionDate', function (Options $options, $value) {
                if (null === $value || $value instanceof \DateTime) {
                    return $value;
                }

                return \DateTime::createFromFormat("Ymd\TH:i:s", $value);
            })
            ->setDefault('refusedDocs', [])->setAllowedTypes('refusedDocs', ['null', 'array'])
            ->setDefault('refusalComment', null)->setAllowedTypes('refusalComment', ['null', 'string'])
            ->setDefault('redirectPolicy', null)->setAllowedTypes('redirectPolicy', ['null', 'string'])
            ->setDefault('redirectWait', null)->setAllowedTypes('redirectWait', ['null', 'int'])
            ->setDefault('idDocuments', null)->setAllowedTypes('idDocuments', ['array', 'null'])
        ;
    }

    /**
     * @throws UndefinedOptionsException If an option name is undefined
     * @throws InvalidOptionsException   If an option doesn't fulfill the language specified validation rules
     * @throws MissingOptionsException   If a required option is missing
     * @throws OptionDefinitionException If there is a cyclic dependency between lazy options and/or normalizers
     * @throws NoSuchOptionException     If a lazy option reads an unavailable option
     * @throws AccessException           If called from a lazy option or normalizer
     */
    public static function createFromArray(array $options): self
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return (new self())
            ->setStatus($resolvedOptions['status'])
            ->setError($resolvedOptions['error'])
            ->setCertificateInfo($resolvedOptions['certificateInfo'])
            ->setUrl($resolvedOptions['url'])
            ->setId($resolvedOptions['id'])
            ->setEmail($resolvedOptions['email'])
            ->setFirstName($resolvedOptions['firstName'])
            ->setLastName($resolvedOptions['lastName'])
            ->setActionDate($resolvedOptions['actionDate'])
            ->setRefusedDocs($resolvedOptions['refusedDocs'])
            ->setRefusalComment($resolvedOptions['refusalComment'])
            ->setRedirectPolicy($resolvedOptions['redirectPolicy'])
            ->setRedirectWait($resolvedOptions['redirectWait'])
            ->setIdDocuments($resolvedOptions['idDocuments'])
        ;
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

    public function setError(?string $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setCertificateInfo(?CertificateInfo $certificateInfo): self
    {
        $this->certificateInfo = $certificateInfo;

        return $this;
    }

    public function getCertificateInfo(): ?CertificateInfo
    {
        return $this->certificateInfo;
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

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setActionDate(?\DateTime $actionDate): self
    {
        $this->actionDate = $actionDate;

        return $this;
    }

    public function getActionDate(): ?\DateTime
    {
        return $this->actionDate;
    }

    public function setRefusedDocs(array $refusedDocs): self
    {
        $this->refusedDocs = $refusedDocs;

        return $this;
    }

    public function getRefusedDocs(): array
    {
        return $this->refusedDocs;
    }

    public function setRefusalComment(?string $refusalComment): self
    {
        $this->refusalComment = $refusalComment;

        return $this;
    }

    public function getRefusalComment(): ?string
    {
        return $this->refusalComment;
    }

    public function setRediRectPolicy(?string $redirectPolicy): self
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

    public function getIdDocuments(): ?array
    {
        return $this->idDocuments;
    }

    public function setIdDocuments(?array $idDocuments): SignerInfo
    {
        $this->idDocuments = $idDocuments;

        return $this;
    }
}
