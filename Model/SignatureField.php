<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignatureField
{
    protected ?string $name;

    protected int $page;

    protected ?int $x;

    protected ?int $y;

    protected int $signerIndex;

    public function __construct(int $signerIndex)
    {
        $this->signerIndex = $signerIndex;
        $this->name = null;
        $this->page = 1;
        $this->x = null;
        $this->y = null;
    }

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', null)->setAllowedTypes('name', ['null', 'string'])
            ->setDefault('page', 1)->setAllowedTypes('page', ['int'])
            ->setDefault('x', null)->setAllowedTypes('x', ['null', 'int'])
            ->setDefault('y', null)->setAllowedTypes('y', ['null', 'int'])
            ->setRequired('signerIndex')->setAllowedTypes('signerIndex', ['int'])
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

        return (new self($resolvedOptions['signerIndex']))
            ->setName($resolvedOptions['name'])
            ->setPage($resolvedOptions['page'])
            ->setX($resolvedOptions['x'])
            ->setY($resolvedOptions['y'])
        ;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setX(?int $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function setY(?int $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    public function setSignerIndex(int $signerIndex): self
    {
        $this->signerIndex = $signerIndex;

        return $this;
    }

    public function getSignerIndex(): int
    {
        return $this->signerIndex;
    }
}
