<?php

namespace Mpp\UniversignBundle\Model;

use DateTimeInterface;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFilter
{
    protected string $requesterEmail;

    protected string $profile;

    protected DateTimeInterface $notBefore;

    protected DateTimeInterface $notAfter;

    protected int $startRange;

    protected int $stopRange;

    protected string $signerId;

    protected DateTimeInterface $notBeforeCompletion;

    protected DateTimeInterface $notAfterCompletion;

    protected int $status;

    protected bool $withAffiliated;

    /**
     * @return TransactionFilter
     */
    public function initiateTransactionFilter(): TransactionFilter
    {
        return new self();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('requesterEmail', null)->setAllowedTypes('requesterEmail', ['string', 'null'])
            ->setDefault('profile', null)->setAllowedTypes('profile', ['string', 'null'])
            ->setDefault('notBefore', null)->setAllowedTypes('notBefore', ['DateTime', DateTimeInterface::class, 'null'])
            ->setDefault('notAfter', null)->setAllowedTypes('notAfter', ['DateTime', DateTimeInterface::class, 'null'])
            ->setDefault('startRange', null)->setAllowedTypes('startRange', ['int', 'null'])
            ->setDefault('stopRange', null)->setAllowedTypes('stopRange', ['int', 'null'])
            ->setDefault('signerId', null)->setAllowedTypes('signerId', ['string', 'null'])
            ->setDefault('notBeforeCompletion', null)->setAllowedTypes('notBeforeCompletion', ['DateTime', DateTimeInterface::class, 'null'])
            ->setDefault('notAfterCompletion', null)->setAllowedTypes('notAfterCompletion', ['DateTime', DateTimeInterface::class, 'null'])
            ->setDefault('status', null)->setAllowedTypes('status', ['int', 'null'])
            ->setDefault('withAffiliated', null)->setAllowedTypes('withAffiliated', ['bool', 'null']);
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

        return(new self())
            ->setRequesterEmail($resolvedOptions['requesterEmail'])
            ->setProfile($resolvedOptions['profile'])
            ->setNotBefore($resolvedOptions['notBefore'])
            ->setNotAfter($resolvedOptions['notAfter'])
            ->setStartRange($resolvedOptions['startRange'])
            ->setStopRange($resolvedOptions['stopRange'])
            ->setSignerId($resolvedOptions['signerId'])
            ->setNotBeforeCompletion($resolvedOptions['notBeforeCompletion'])
            ->setNotAfterCompletion($resolvedOptions['notAfterCompletion'])
            ->setStatus($resolvedOptions['status'])
            ->setWithAffiliated($resolvedOptions['withAffiliated'])
        ;
    }

    /**
     * @param string|null $requesterEmail
     *
     * @return self
     */
    public function setRequesterEmail(?string $requesterEmail): self
    {
        $this->requesterEmail = $requesterEmail;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequesterEmail(): ?string
    {
        return $this->requesterEmail;
    }

    /**
     * @param string|null
     *
     * @return self
     */
    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * @param DateTimeInterface|null $notBefore
     *
     * @return self
     */
    public function setNotBefore(?DateTimeInterface $notBefore): self
    {
        $this->notBefore = $notBefore;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getNotBefore(): ?DateTimeInterface
    {
        return $this->notBefore;
    }

    /**
     * @param DateTimeInterface|null $notAfter
     *
     * @return self
     */
    public function setNotAfter(?DateTimeInterface $notAfter): self
    {
        $this->notAfter = $notAfter;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getNotAfter(): ?DateTimeInterface
    {
        return $this->notAfter;
    }

    /**
     * @param int|null $startRange
     *
     * @return self
     */
    public function setStartRange(?int $startRange): self
    {
        $this->startRange = $startRange;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStartRange(): ?int
    {
        return $this->startRange;
    }

    /**
     * @param int|null $stopRange
     *
     * @return self
     */
    public function setStopRange(?int $stopRange): self
    {
        $this->stopRange = $stopRange;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStopRange(): ?int
    {
        return $this->stopRange;
    }

    /**
     * @param string|null
     *
     * @return self
     */
    public function setSignerId(?string $signerId): self
    {
        $this->signerId = $signerId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSignerId(): ?string
    {
        return $this->signerId;
    }

    /**
     * @param DateTimeInterface|null $notBeforeCompletion
     *
     * @return self
     */
    public function setNotBeforeCompletion(?DateTimeInterface $notBeforeCompletion): self
    {
        $this->notBeforeCompletion = $notBeforeCompletion;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getNotBeforeCompletion(): ?DateTimeInterface
    {
        return $this->notBeforeCompletion;
    }

    /**
     * @param DateTimeInterface|null $notAfterCompletion
     *
     * @return self
     */
    public function setNotAfterCompletion(?DateTimeInterface $notAfterCompletion): self
    {
        $this->notAfterCompletion = $notAfterCompletion;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getNotAfterCompletion(): ?DateTimeInterface
    {
        return $this->notAfterCompletion;
    }

    /**
     * @param int|null $status
     *
     * @return self
     */
    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param bool|null $withAffiliated
     *
     * @return self
     */
    public function setWithAffiliated(?bool $withAffiliated): self
    {
        $this->withAffiliated = $withAffiliated;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getWithAffiliated(): ?bool
    {
        return $this->withAffiliated;
    }
}
