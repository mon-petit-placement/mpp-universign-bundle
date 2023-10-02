<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedirectionConfig
{
    protected ?string $URL;

    protected ?string $displayName;

    public function __construct()
    {
        $this->URL = null;
        $this->displayName = null;
    }

    /**
     * @param OptionsResolver
     */
    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('URL', null)->setAllowedTypes('URL', ['string', 'null'])
            ->setDefault('displayName', null)->setAllowedTypes('displayName', ['string', 'null'])
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
            ->setUrl($resolvedOptions['URL'])
            ->setDisplayName($resolvedOptions['displayName'])
        ;
    }

    public function setUrl(?string $url): self
    {
        $this->URL = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->URL;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }
}
