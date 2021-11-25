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

    public function construct()
    {
        $this->allowManual = false;
        $this->callbackURL = null;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
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
            ->setIdDocument($resolvedOptions['idDocument'])
            ->setPersonalInfo($resolvedOptions['personalInfo'])
            ->setAllowManual($resolvedOptions['allowManual'])
            ->setCallbackURL($resolvedOptions['callbackUrl'])
        ;
    }

    /**
     * @return IdDocument
     */
    public function getIdDocument(): IdDocument
    {
        return $this->idDocument;
    }

    /**
     * @param IdDocument $idDocument
     *
     * @return self
     */
    public function setIdDocument(IdDocument $idDocument): self
    {
        $this->idDocument = $idDocument;

        return $this;
    }

    /**
     * @return PersonalInfo
     */
    public function getPersonalInfo(): PersonalInfo
    {
        return $this->personalInfo;
    }

    /**
     * @param PersonalInfo $personalInfo
     *
     * @return self
     */
    public function setPersonalInfo(PersonalInfo $personalInfo): self
    {
        $this->personalInfo = $personalInfo;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowManual(): bool
    {
        return $this->allowManual;
    }

    /**
     * @param bool $allowManual
     *
     * @return self
     */
    public function setAllowManual(bool $allowManual): self
    {
        $this->allowManual = $allowManual;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCallbackURL(): ?string
    {
        return $this->callbackURL;
    }

    /**
     * @param string|null $callbackURL
     *
     * @return self
     */
    public function setCallbackURL(?string $callbackURL): self
    {
        $this->callbackURL = $callbackURL;

        return $this;
    }
}
