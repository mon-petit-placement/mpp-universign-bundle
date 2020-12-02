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
    const CERTIFICATE_LEVEL_NONE = 'none';
    const CERTIFICATE_LEVEL_ADVANCED = 'advanced';
    const CERTIFICATE_LEVEL_CERTIFIED = 'certified';

    const CERTIFICATE_STATUS_VALID = 'valid';
    const CERTIFICATE_STATUS_REVOKED = 'revoked';
    const CERTIFICATE_STATUS_AWAITING_VALIDATION = 'awaiting-validation';
    /**
     * @var string|null
     */
    protected $firstname;

    /**
     * @var string|null
     */
    protected $lastname;

    /**
     * @var string|null
     */
    protected $mobile;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $certificateLevel;

    /**
     * @var string
     */
    protected $certificateStatus;

    /**
     * @var CertificateInfo
     */
    protected $certificateInfo;

    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('firstname', null)->setAllowedTypes('firstname', ['null', 'string'])
            ->setDefault('lastname', null)->setAllowedTypes('lastname', ['null', 'string'])
            ->setDefault('mobile', null)->setAllowedTypes('mobile', ['null', 'string'])
            ->setRequired('email')->setAllowedTypes('email', ['string'])
            ->setRequired('certificateLevel')->setAllowedTypes('certificateLevel', ['string'])
            ->setRequired('certificateStatus')->setAllowedTypes('certificateStatus', ['string'])
            ->setRequired('certificateInfo')->setAllowedTypes('certificateInfo', ['array', CertificateInfo::class])->setNormalizer('certificateInfo', function(Options $options, $value) {
                if ($value instanceof CertificateInfo) {
                    return $value;
                }

                return CertificateInfo::createFromArray($value);
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
            ->setFirstname($resolvedOptions['subject'])
            ->setLastname($resolvedOptions['issuer'])
            ->setEmail($resolvedOptions['issuer'])
            ->setCertificateLevel($resolvedOptions['issuer'])
            ->setCertificateStatus($resolvedOptions['serial'])
            ->setCertificateInfo($resolvedOptions['issuer'])
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
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getCertificateLevel(): string
    {
        return $this->certificateLevel;
    }

    /**
     * @param string $certificateLevel
     *
     * @return self
     */
    public function setCertificateLevel(string $certificateLevel): self
    {
        $this->certificateLevel = $certificateLevel;

        return $this;
    }

    /**
     * @return string
     */
    public function getCertificateStatus(): string
    {
        return $this->certificateStatus;
    }

    /**
     * @param string $certificateStatus
     *
     * @return self
     */
    public function setCertificateStatus(string $certificateStatus): self
    {
        $this->certificateStatus = $certificateStatus;

        return $this;
    }

    /**
     * @return CertificateInfo
     */
    public function getCertificateInfo(): CertificateInfo
    {
        return $this->certificateInfo;
    }

    /**
     * @param CertificateInfo $certificateInfo
     *
     * @return self
     */
    public function setCertificateInfo(CertificateInfo $certificateInfo): self
    {
        $this->certificateInfo = $certificateInfo;

        return $this;
    }
}
