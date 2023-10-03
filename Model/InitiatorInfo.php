<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InitiatorInfo
{
    protected ?string $email;

    protected ?string $firstName;

    protected ?string $lastName;

    public function __construct()
    {
        $this->email = null;
        $this->firstName = null;
        $this->lastName = null;
    }

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('email', null)->setAllowedTypes('email', ['null', 'string'])
            ->setDefault('firstName', null)->setAllowedTypes('firstName', ['null', 'string'])
            ->setDefault('lastName', null)->setAllowedTypes('lastName', ['null', 'string'])
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
    public static function createFromArray(array $options): ?self
    {
        if (null === $options) {
            return null;
        }

        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return (new self())
            ->setEmail($resolvedOptions['email'])
            ->setFirstName($resolvedOptions['firstName'])
            ->setLastName($resolvedOptions['lastName'])
        ;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
