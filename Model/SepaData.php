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

class SepaData
{
    protected string $rum;

    protected string $ics;

    protected string $iban;

    protected string $bic;

    protected bool $recuring;

    protected ?SepaThirdParty $debtor;

    protected ?SepaThirdParty $creditor;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('rum')->setAllowedTypes('rum', ['string'])
            ->setRequired('ics')->setAllowedTypes('ics', ['string'])
            ->setRequired('iban')->setAllowedTypes('iban', ['string'])
            ->setRequired('bic')->setAllowedTypes('bic', ['string'])
            ->setRequired('recurring')->setAllowedTypes('recurring', ['bool'])
            ->setRequired('debtor')->setAllowedTypes('debtor', ['array', SepaThirdParty::class])->setNormalizer('debtor', function (Options $options, $value): SepaThirdParty {
                if (is_array($value)) {
                    return SepaThirdParty::createFromArray($value);
                }

                return $value;
            })
            ->setRequired('creditor')->setAllowedTypes('creditor', ['array', SepaThirdParty::class])->setNormalizer('creditor', function (Options $options, $value): SepaThirdParty {
                if (is_array($value)) {
                    return SepaThirdParty::createFromArray($value);
                }

                return $value;
            })
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
            ->setRum($resolvedOptions['rum'])
            ->setIcs($resolvedOptions['ics'])
            ->setIban($resolvedOptions['iban'])
            ->setBic($resolvedOptions['bic'])
            ->setRecuring($resolvedOptions['recurring'])
            ->setDebtor($resolvedOptions['debtor'])
            ->setCreditor($resolvedOptions['creditor'])
        ;
    }

    /**
     * @param string|null $rum
     *
     * @return self
     */
    public function setRum(?string $rum): self
    {
        $this->rum = $rum;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRum(): ?string
    {
        return $this->rum;
    }

    /**
     * @param string|null $ics
     *
     * @return self
     */
    public function setIcs(?string $ics): self
    {
        $this->ics = $ics;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIcs(): ?string
    {
        return $this->ics;
    }

    /**
     * @param string|null $iban
     *
     * @return self
     */
    public function setIban(?string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @param string|null $bic
     *
     * @return self
     */
    public function setBic(?string $bic): self
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBic(): ?string
    {
        return $this->bic;
    }

    /**
     * @param bool|null $recuring
     *
     * @return self
     */
    public function setRecuring(?bool $recuring): self
    {
        $this->recuring = $recuring;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getRecuring(): ?bool
    {
        return $this->recuring;
    }

    /**
     * @param SepaThirdParty|null $debtor
     *
     * @return self
     */
    public function setDebtor(?SepaThirdParty $debtor): self
    {
        $this->debtor = $debtor;

        return $this;
    }

    /**
     * @return SepaThirdParty|null
     */
    public function getDebtor(): ?SepaThirdParty
    {
        return $this->debtor;
    }

    /**
     * @param SepaThirdParty|null $creditor
     *
     * @return self
     */
    public function setCreditor(?SepaThirdParty $creditor): self
    {
        $this->creditor = $creditor;

        return $this;
    }

    /**
     * @return SepaThirdParty|null
     */
    public function getCreditor(): ?SepaThirdParty
    {
        return $this->creditor;
    }
}
