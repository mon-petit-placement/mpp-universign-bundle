<?php

namespace Mpp\UniversignBundle\Model;

use Mpp\UniversignBundle\Model\XmlRpc\Base64;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocSignatureField extends SignatureField
{
    protected ?string $patternName;

    protected ?string $label;

    protected ?Base64 $image;

    public static function configureData(OptionsResolver $resolver): void
    {
        parent::configureData($resolver);

        $resolver
            ->setDefault('patternName', null)->setAllowedTypes('patternName', ['string', 'null'])
            ->setDefault('label', null)->setAllowedTypes('label', ['string', 'null'])
            ->setDefault('image', null)->setAllowedTypes('image', [Base64::class, 'null'])
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
            ->setPatternName($resolvedOptions['patternName'])
            ->setLabel($resolvedOptions['label'])
            ->setImage($resolvedOptions['image'])
            ->setName($resolvedOptions['name'])
            ->setPage($resolvedOptions['page'])
            ->setX($resolvedOptions['x'])
            ->setY($resolvedOptions['y'])
            ->setSignerIndex($resolvedOptions['signerIndex'])
        ;
    }

    public function setPatternName(?string $patternName): self
    {
        $this->patternName = $patternName;

        return $this;
    }

    public function getPatternName(): string
    {
        return $this->patternName;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setImage(?Base64 $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImage(): Base64
    {
        return $this->image;
    }
}
