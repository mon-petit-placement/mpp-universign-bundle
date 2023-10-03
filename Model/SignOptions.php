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
    public const SINGNATURE_FORMAT_PADES = 'PADES';
    public const SINGNATURE_FORMAT_PADES_COMP = 'PADES-COMP';
    public const SINGNATURE_FORMAT_ISO32000_1 = 'ISO-32000-1';

    protected ?string $profile;

    protected ?SignatureField $signatureField;

    protected ?string $reason;

    protected ?string $location;

    protected ?string $signatureFormat;

    protected ?string $language;

    protected ?string $patternName;

    public function __construct()
    {
        $this->profile = 'default';
        $this->signatureField = null;
        $this->reason = null;
        $this->location = null;
        $this->signatureFormat = self::SINGNATURE_FORMAT_PADES;
        $this->language = 'en';
        $this->patternName = null;
    }

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('profile', 'default')->setAllowedTypes('profile', ['string'])
            ->setDefault('signatureField', null)->setAllowedTypes('signatureField', ['null', 'array', SignatureField::class])->setNormalizer('signatureField', function (Options $options, $value) {
                if (null === $value || $value instanceof SignatureField) {
                    return $value;
                }

                return SignatureField::createFromArray($value);
            })
            ->setDefault('reason', null)->setAllowedTypes('reason', ['null', 'string'])
            ->setDefault('location', null)->setAllowedTypes('location', ['null', 'string'])
            ->setDefault('signatureFormat', self::SINGNATURE_FORMAT_PADES)->setAllowedValues('signatureFormat', [self::SINGNATURE_FORMAT_PADES, self::SINGNATURE_FORMAT_PADES_COMP, self::SINGNATURE_FORMAT_ISO32000_1])
            ->setDefault('language', 'en')->setAllowedValues('language', [Language::BULGARIAN, Language::CATALAN, Language::GERMAN, Language::ENGLISH, Language::SPANISH, Language::FRENCH, Language::ITALIAN, Language::DUTCH, Language::POLISH, Language::PORTUGUESE, Language::ROMANIAN])
            ->setDefault('patternName', null)->setAllowedTypes('patternName', ['null', 'string'])
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
            ->setProfile($resolvedOptions['profile'])
            ->setSignatureField($resolvedOptions['signatureField'])
            ->setReason($resolvedOptions['reason'])
            ->setLocation($resolvedOptions['location'])
            ->setSignatureFormat($resolvedOptions['signatureFormat'])
            ->setLanguage($resolvedOptions['language'])
            ->setPatternName($resolvedOptions['patternName'])
        ;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;
        return $this;
    }

    public function getSignatureField(): ?SignatureField
    {
        return $this->signatureField;
    }

    public function setSignatureField(?SignatureField $signatureField): self
    {
        $this->signatureField = $signatureField;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getSignatureFormat(): ?string
    {
        return $this->signatureFormat;
    }

    public function setSignatureFormat(?string $signatureFormat): self
    {
        $this->signatureFormat = $signatureFormat;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function getPatternName(): ?string
    {
        return $this->patternName;
    }

    public function setPatternName(?string $patternName): self
    {
        $this->patternName = $patternName;
        return $this;
    }
}
