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

class SignatureField
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * @var int
     */
    protected $signerIndex;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('name', null)->setAllowedTypes('name', ['null', 'string'])
            ->setDefault('page', 1)->setAllowedTypes('page', ['int'])
            ->setDefault('x', null)->setAllowedTypes('x', ['null', 'int'])
            ->setDefault('y', null)->setAllowedTypes('y', ['null', 'int'])
            ->setRequired('signerIndex')->setAllowedTypes('signerIndex', ['null', 'int'])
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
        ;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param int $page
     *
     * @return self
     */
    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $x;
     *
     * @return self
     */
    public function setX(?int $x): self
    {
        $this->x = $x;

        return $this;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $y;
     *
     * @return self
     */
    public function setY(?int $y): self
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $signerIndex
     *
     * @return self
     */
    public function setSignerIndex(?int $signerIndex): self
    {
        $this->signerIndex = $signerIndex;

        return $this;
    }

    /**
     * @return int
     */
    public function getSignerIndex(): int
    {
        return $this->signerIndex;
    }
}
