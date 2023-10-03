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

class ValidationRequest
{
    protected IdDocument $idDocument;

    protected PersonalInfo $personalInfo;

    protected bool $allowManual;

    protected ?string $callbackURL;

    public function __construct(IdDocument $idDocument, PersonalInfo $personalInfo, bool $allowManual)
    {
        $this->idDocument = $idDocument;
        $this->personalInfo = $personalInfo;
        $this->allowManual = $allowManual;
        $this->callbackURL = null;
    }

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('idDocument')->setAllowedTypes('idDocument', ['array', IdDocument::class])->setNormalizer('idDocument', function (Options $options, $value) {
                if ($value instanceof IdDocument) {
                    return $value;
                }

                return IdDocument::createFromArray($value);
            })
            ->setRequired('personalInfo')->setAllowedTypes('personalInfo', ['array', PersonalInfo::class])->setNormalizer('personalInfo', function (Options $options, $value) {
                if ($value instanceof PersonalInfo) {
                    return $value;
                }

                return PersonalInfo::createFromArray($value);
            })
            ->setDefault('callbackUrl', null)->setAllowedTypes('callbackUrl', ['string', 'null'])
            ->setDefault('allowManual', false)->setAllowedTypes('allowManual', ['bool'])
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

        return (new self(
            $resolvedOptions['idDocument'],
            $resolvedOptions['personalInfo'],
            $resolvedOptions['allowManual'],
        ))->setCallbackURL($resolvedOptions['callbackUrl'])
        ;
    }

    public function getIdDocument(): IdDocument
    {
        return $this->idDocument;
    }

    public function setIdDocument(IdDocument $idDocument): self
    {
        $this->idDocument = $idDocument;

        return $this;
    }

    public function getPersonalInfo(): PersonalInfo
    {
        return $this->personalInfo;
    }

    public function setPersonalInfo(PersonalInfo $personalInfo): self
    {
        $this->personalInfo = $personalInfo;

        return $this;
    }

    public function isAllowManual(): bool
    {
        return $this->allowManual;
    }

    public function setAllowManual(bool $allowManual): self
    {
        $this->allowManual = $allowManual;

        return $this;
    }

    public function getCallbackURL(): ?string
    {
        return $this->callbackURL;
    }

    public function setCallbackURL(?string $callbackURL): self
    {
        $this->callbackURL = $callbackURL;

        return $this;
    }
}
