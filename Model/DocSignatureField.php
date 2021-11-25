<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocSignatureField extends SignatureField
{
    protected string $patternName;

    protected string $label;

    protected string $image;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        parent::configureData($resolver);

        $resolver
            ->setDefault('patternName', null)->setAllowedTypes('patternName', ['string', 'null'])
            ->setDefault('label', null)->setAllowedTypes('label', ['string', 'null'])
            ->setDefault('image', null)->setAllowedTypes('image', ['string', 'null'])
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
            ->setName($resolvedOptions['name'])
            ->setPage($resolvedOptions['page'])
            ->setX($resolvedOptions['x'])
            ->setY($resolvedOptions['y'])
            ->setSignerIndex($resolvedOptions['signerIndex'])
            ->setPatternName($resolvedOptions['patternName'])
            ->setLabel($resolvedOptions['label'])
            ->setImage($resolvedOptions['image'])
        ;
    }

    /**
     * @param string|null $patternName
     *
     * @return self
     */
    public function setPatternName(?string $patternName): self
    {
        $this->patternName = $patternName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPatternName(): string
    {
        return $this->patternName;
    }

    /**
     * @param string|null $label
     *
     * @return self
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param array|null $image
     *
     * @return self
     */
    public function setImage(?array $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
