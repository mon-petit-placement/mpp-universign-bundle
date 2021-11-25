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

    protected ?string $lastname;

    protected ?string $mobile;

    protected ?string $email;

    protected ?string $certificateLevel;

    protected ?string $certificateStatus;

    protected ?RaCertificateInfo $certificateInfo;

    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('firstname', null)->setAllowedTypes('firstname', ['null', 'string'])
            ->setDefault('lastname', null)->setAllowedTypes('lastname', ['null', 'string'])
            ->setDefault('mobile', null)->setAllowedTypes('mobile', ['null', 'string'])
            ->setDefault('email', null)->setAllowedTypes('email', ['string', 'null'])
            ->setDefault('certificateLevel', null)->setAllowedTypes('certificateLevel', ['string', 'null'])
            ->setDefault('certificateStatus', null)->setAllowedTypes('certificateStatus', ['string', 'null'])
            ->setDefault('certificateInfo', null)->setAllowedTypes('certificateInfo', ['array', 'null', RaCertificateInfo::class])->setNormalizer('certificateInfo', function(Options $options, $value) {
                if (null === $value || $value instanceof RaCertificateInfo) {
                    return $value;
                }

                return RaCertificateInfo::createFromArray($value);
            })
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
            ->setFirstname($resolvedOptions['firstname'])
            ->setLastname($resolvedOptions['lastname'])
            ->setEmail($resolvedOptions['email'])
            ->setMobile($resolvedOptions['mobile'])
            ->setCertificateLevel($resolvedOptions['certificateLevel'])
            ->setCertificateStatus($resolvedOptions['certificateStatus'])
            ->setCertificateInfo($resolvedOptions['certificateInfo'])
        ;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     *
     * @return self
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     *
     * @return self
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    /**
     * @param string|null $mobile
     *
     * @return self
     */
    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

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
    public function getCertificateLevel(): ?string
    {
        return $this->certificateLevel;
    }

    /**
     * @param string|null $certificateLevel
     *
     * @return self
     */
    public function setCertificateLevel(?string $certificateLevel): self
    {
        $this->certificateLevel = $certificateLevel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCertificateStatus(): ?string
    {
        return $this->certificateStatus;
    }

    /**
     * @param string|null $certificateStatus
     *
     * @return self
     */
    public function setCertificateStatus(?string $certificateStatus): self
    {
        $this->certificateStatus = $certificateStatus;

        return $this;
    }

    /**
     * @return RaCertificateInfo|null
     */
    public function getCertificateInfo(): ?RaCertificateInfo
    {
        return $this->certificateInfo;
    }

    /**
     * @param RaCertificateInfo|null $certificateInfo
     *
     * @return self
     */
    public function setCertificateInfo(?RaCertificateInfo $certificateInfo): self
    {
        $this->certificateInfo = $certificateInfo;

        return $this;
    }
}
