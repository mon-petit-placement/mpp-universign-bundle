<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificateInfo
{
    protected string $subject;

    protected string $issuer;

    protected string $serial;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('subject', null)->setAllowedTypes('subject', ['null', 'string'])
            ->setDefault('issuer', null)->setAllowedTypes('issuer', ['null', 'string'])
            ->setDefault('serial', null)->setAllowedTypes('serial', ['null', 'string'])
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
            ->setSubject($resolvedOptions['subject'])
            ->setIssuer($resolvedOptions['issuer'])
            ->setSerial($resolvedOptions['serial'])
        ;
    }

    /**
     * @param string|null $subject
     *
     * @return self
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $issuer
     *
     * @return self
     */
    public function setIssuer(?string $issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    /**
     * @param string|null $serial
     *
     * @return self
     */
    public function setSerial(?string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSerial(): ?string
    {
        return $this->serial;
    }
}
