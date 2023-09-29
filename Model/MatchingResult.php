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

class MatchingResult
{
    public const CERTIFICATE_LEVEL_NONE = 'none';
    public const CERTIFICATE_LEVEL_ADVANCED = 'advanced';
    public const CERTIFICATE_LEVEL_CERTIFIED = 'certified';

    public const CERTIFICATE_STATUS_VALID = 'valid';
    public const CERTIFICATE_STATUS_REVOKED = 'revoked';
    public const CERTIFICATE_STATUS_AWAITING_VALIDATION = 'awaiting-validation';

    protected ?string $firstname;

    protected ?string $lastname;

    protected ?string $mobile;

    protected ?string $email;

    protected ?string $certificateLevel;

    protected ?string $certificateStatus;

    protected ?RaCertificateInfo $certificateInfo;

    protected ?\DateTime $expirationDate;

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('firstname', null)->setAllowedTypes('firstname', ['null', 'string'])
            ->setDefault('lastname', null)->setAllowedTypes('lastname', ['null', 'string'])
            ->setDefault('mobile', null)->setAllowedTypes('mobile', ['null', 'string'])
            ->setDefault('email', null)->setAllowedTypes('email', ['string', 'null'])
            ->setDefault('certificateLevel', null)->setAllowedTypes('certificateLevel', ['string', 'null'])
            ->setDefault('certificateStatus', null)->setAllowedTypes('certificateStatus', ['string', 'null'])
            ->setDefault('expirationDate', null)->setAllowedTypes('expirationDate', ['string', 'null', \DateTime::class])->setNormalizer('expirationDate', function (Options $options, $value) {
                if (!is_string($value)) {
                    return $value;
                }

                return \DateTime::createFromFormat('Ymd\TH:i:s', $value, new \DateTimeZone('UTC'));
            })
            ->setDefault('certificateInfo', null)->setAllowedTypes('certificateInfo', ['array', 'null', RaCertificateInfo::class])->setNormalizer('certificateInfo', function (Options $options, $value) {
                if (null === $value || $value instanceof RaCertificateInfo) {
                    return $value;
                }

                return RaCertificateInfo::createFromArray($value);
            })
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
            ->setFirstname($resolvedOptions['firstname'])
            ->setLastname($resolvedOptions['lastname'])
            ->setEmail($resolvedOptions['email'])
            ->setMobile($resolvedOptions['mobile'])
            ->setCertificateLevel($resolvedOptions['certificateLevel'])
            ->setCertificateStatus($resolvedOptions['certificateStatus'])
            ->setCertificateInfo($resolvedOptions['certificateInfo'])
            ->setExpirationDate($resolvedOptions['expirationDate'])
        ;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCertificateLevel(): ?string
    {
        return $this->certificateLevel;
    }

    public function setCertificateLevel(?string $certificateLevel): self
    {
        $this->certificateLevel = $certificateLevel;

        return $this;
    }

    public function getCertificateStatus(): ?string
    {
        return $this->certificateStatus;
    }

    public function setCertificateStatus(?string $certificateStatus): self
    {
        $this->certificateStatus = $certificateStatus;

        return $this;
    }

    public function getCertificateInfo(): ?RaCertificateInfo
    {
        return $this->certificateInfo;
    }

    public function setCertificateInfo(?RaCertificateInfo $certificateInfo): self
    {
        $this->certificateInfo = $certificateInfo;

        return $this;
    }

    public function getExpirationDate(): ?\DateTime
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(?\DateTime $expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }
}
