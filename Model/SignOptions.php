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

class SignOptions
{
    const SINGNATURE_FORMAT_PADES = 'PADES';
    const SINGNATURE_FORMAT_PADES_COMP = 'PADES-COMP';
    const SINGNATURE_FORMAT_ISO32000_1 = 'ISO-32000-1';

    /**
     * @var string|null
     */
    protected $profile;

    /**
     * @var SignatureField|null
     */
    protected $signatureField;

    /**
     * @var string|null
     */
    protected $reason;

    /**
     * @var string|null
     */
    protected $location;

    /**
     * @var string|null
     */
    protected $signatureFormat;

    /**
     * @var string|null
     */
    protected $language;

    /**
     * @var string|null
     */
    protected $patternName;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('profile', 'default')->setAllowedTypes('profile', ['string'])
            ->setDefault('signatureField', null)->setAllowedTypes('signatureField', ['null', 'array', SignatureField::class])->setNormalizer('signatureField', function(Options $options, $value) {
                if (null === $value || $value instanceof SignatureField) {
                    return $value;
                }

                return SignatureField::createFromArray($vvalue);
            })
            ->setDefault('reason', null)->setAllowedTypes('reason', ['null', 'string'])
            ->setDefault('location', null)->setAllowedTypes('location', ['null', 'string'])
            ->setDefault('signatureFormat', null)->setAllowedValues('signatureFormat', [self::SINGNATURE_FORMAT_PADES, self::SINGNATURE_FORMAT_PADES_COMP, self::SINGNATURE_FORMAT_ISO32000_1])
            ->setDefault('language', 'en')->setAllowedValues('language', [Language::BULGARIAN, Language::CATALAN, Language::GERMAN, Language::ENGLISH, Language::SPANISH, Language::FRENCH, Language::ITALIAN, Language::DUTCH, Language::POLISH, Language::PORTUGUESE, Language::ROMANIAN])
            ->setDefault('patternName', null)->setAllowedTypes('patternName', ['null', 'string'])*
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
            ->setProfile($resolvedOptions['profile'])
            ->setSignatureField($resolvedOptions['signatureField'])
            ->setReason($resolvedOptions['reason'])
            ->setLocation($resolvedOptions['location'])
            ->setSignatureFormat($resolvedOptions['signatureFormat'])
            ->setLanguage($resolvedOptions['language'])
            ->setPatternName($resolvedOptions['patternName'])
        ;
    }

    /**
     * @return string|null
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * @param string|null $profile
     * @return SignOptions
     */
    public function setProfile(?string $profile): SignOptions
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return SignatureField|null
     */
    public function getSignatureField(): ?SignatureField
    {
        return $this->signatureField;
    }

    /**
     * @param SignatureField|null $signatureField
     * @return SignOptions
     */
    public function setSignatureField(?SignatureField $signatureField): SignOptions
    {
        $this->signatureField = $signatureField;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param string|null $reason
     * @return SignOptions
     */
    public function setReason(?string $reason): SignOptions
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     * @return SignOptions
     */
    public function setLocation(?string $location): SignOptions
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSignatureFormat(): ?string
    {
        return $this->signatureFormat;
    }

    /**
     * @param string|null $signatureFormat
     * @return SignOptions
     */
    public function setSignatureFormat(?string $signatureFormat): SignOptions
    {
        $this->signatureFormat = $signatureFormat;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string|null $language
     * @return SignOptions
     */
    public function setLanguage(?string $language): SignOptions
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPatternName(): ?string
    {
        return $this->patternName;
    }

    /**
     * @param string|null $patternName
     * @return SignOptions
     */
    public function setPatternName(?string $patternName): SignOptions
    {
        $this->patternName = $patternName;
        return $this;
    }
}
