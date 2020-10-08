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

    public function __construct()
    {
        $this->refusedDocs = [];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('status', null)->setAllowedTypes('status', ['null', 'string'])
            ->setDefault('error', null)->setAllowedTypes('error', ['null', 'string'])
            ->setDefault('certificateInfo', null)->setAllowedTypes('certificateInfo', ['null', 'array', CertificateInfo::class])->setNormalizer('certificateInfo', function(Options $options, $value) {
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
            ->setDefault('actionDate', null)->setAllowedTypes('actionDate', ['null', 'string', \DateTime::class])->setNormalizer('actionDate', function(Options $options, $value) {
                if (null === $value || $value instanceof \DateTime) {
                    return $value;
                }

                return \DateTime::createFromFormat("Ymd\TH:i:s", $value);
            })
            ->setDefault('refusedDocs', null)->setAllowedTypes('refusedDocs', ['null', 'string'])
            ->setDefault('refusalComment', null)->setAllowedTypes('refusalComment', ['null', 'string'])
            ->setDefault('redirectPolicy', null)->setAllowedTypes('redirectPolicy', ['null', 'string'])
            ->setDefault('redirectWait', null)->setAllowedTypes('redirectWait', ['null', 'int'])
        ;
    }

    /**
     * @param array $options
     *
     * @return self
     *
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
        ;
    }

    /**
     * @param string|null $date
     *
     * @return \DateTime|null
     */
    private static function createDate(?string $date): ?\DateTime {
        if (is_null($date)) {
            return null;
        }

        return \DateTime::createFromFormat("Ymd\TH:i:s", $date);
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
