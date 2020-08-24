<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SepaData
{
    /**
     * @var string
     */
    protected $rum;

    /**
     * @var string
     */
    protected $ics;

    /**
     * @var string
     */
    protected $iban;

    /**
     * @var string
     */
    protected $bic;

    /**
     * @var bool
     */
    protected $recuring;

    /**
     * @var SepaThirdParty
     */
    protected $debtor;

    /**
     * @var SepaThirdParty
     */
    protected $creditor;

    public function __construct()
    {
        $this->debtor = [];
        $this->creditor = [];
    }

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
            ->setRequired('debtor')->setAllowedTypes('debtor', ['array', '\SepaThirdParty'])->setNormalizer('debtor', function(Options $option, $value): SepaThirdParty {
                if (is_array($value)) {
                    return SepaThirdParty::createFromArray($value);
                }

                return $value;
            })
            ->setRequired('creditor')->setAllowedTypes('creditor', ['array', '\SepaThirdParty'])->setNormalizer('creditor', function(Options $option, $value): SepaThirdParty {
                if (is_array($value)) {
                    return SepaThirdParty::createFromArray($value);
                }

                return $value;
            })
        ;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function createFromArray(array $data): self
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedData = $resolver->resolve($data);

        return (new SepaData())
            ->setRum($resolvedData['rum'])
            ->setIcs($resolvedData['ics'])
            ->setIban($resolvedData['iban'])
            ->setBic($resolvedData['bic'])
            ->setRecuring($resolvedData['recurring'])
            ->setDebtor($resolvedData['debtor'])
            ->setCreditor($resolvedData['creditor'])
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