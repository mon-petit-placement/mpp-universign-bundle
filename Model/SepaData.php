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
            ->setRequired('debtor')->setAllowedTypes('debtor', ['array'])
            ->setRequired('creditor')->setAllowedTypes('creditor', ['array'])
        ;
    }

    /**
     * @param array $data
     *
     * @return SepaData
     */
    public static function createFromArray(array $data): SepaData
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedData = $resolver->resolve($data);
        dump($resolvedData);

        $sepaData = new SepaData();

        $sepaData
            ->setRum($resolvedData['rum'])
            ->setIcs($resolvedData['ics'])
            ->setIban($resolvedData['iban'])
            ->setBic($resolvedData['bic'])
            ->setRecuring($resolvedData['recuring'])
            ->setDebtor($resolvedData['debtor'])
            ->setCreditor($resolvedData['creditor'])
        ;

        return $sepaData;
    }

    /**
     * @param string $rum
     *
     * @return self
     */
    public function setRum(string $rum): self
    {
        $this->rum = $rum;

        return $this;
    }

    /**
     * @return string
     */
    public function getRum(): string
    {
        return $this->rum;
    }

    /**
     * @param string $ics
     *
     * @return self
     */
    public function setIcs(string $ics): self
    {
        $this->ics = $ics;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcs(): string
    {
        return $this->ics;
    }

    /**
     * @param string $iban
     *
     * @return self
     */
    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @param string $bic
     *
     * @return self
     */
    public function setBic(string $bic): self
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }

    /**
     * @param bool $recuring
     *
     * @return self
     */
    public function setRecuring(bool $recuring): self
    {
        $this->recuring = $recuring;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRecuring(): bool
    {
        return $this->recuring;
    }

    /**
     * @param array $debtor
     *
     * @return self
     */
    public function setDebtor(SepaThirdParty $debtor): self
    {
        $this->debtor = SepaThirdParty::createFromArray($debtor);

        return $this;
    }

    /**
     * @return SepaThirdParty
     */
    public function getDebtor(): SepaThirdParty
    {
        return $this->debtor;
    }

    /**
     * @param array $creditor
     *
     * @return self
     */
    public function setCreditor(SepaThirdParty $creditor): self
    {
        $this->creditor = SepaThirdParty::createFromArray($creditor);

        return $this;
    }

    /**
     * @return SepaThirdParty
     */
    public function getCreditor(): SepaThirdParty
    {
        return $this->creditor;
    }

}