<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFilter
{
    protected ?string $requesterEmail;

    protected ?string $profile;

    protected ?\DateTimeInterface $notBefore;

    protected ?\DateTimeInterface $notAfter;

    protected ?int $startRange;

    protected ?int $stopRange;

    protected ?string $signerId;

    protected ?\DateTimeInterface $notBeforeCompletion;

    protected ?\DateTimeInterface $notAfterCompletion;

    protected ?int $status;

    protected ?bool $withAffiliated;

    public function __construct()
    {
        $this->requesterEmail = null;
        $this->profile = null;
        $this->notBefore = null;
        $this->notAfter = null;
        $this->startRange = null;
        $this->stopRange = null;
        $this->signerId = null;
        $this->notBeforeCompletion = null;
        $this->notAfterCompletion = null;
        $this->status = null;
        $this->withAffiliated = null;
    }

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('requesterEmail', null)->setAllowedTypes('requesterEmail', ['string', 'null'])
            ->setDefault('profile', null)->setAllowedTypes('profile', ['string', 'null'])
            ->setDefault('notBefore', null)->setAllowedTypes('notBefore', [\DateTimeInterface::class, 'null'])
            ->setDefault('notAfter', null)->setAllowedTypes('notAfter', [\DateTimeInterface::class, 'null'])
            ->setDefault('startRange', null)->setAllowedTypes('startRange', ['int', 'null'])
            ->setDefault('stopRange', null)->setAllowedTypes('stopRange', ['int', 'null'])
            ->setDefault('signerId', null)->setAllowedTypes('signerId', ['string', 'null'])
            ->setDefault('notBeforeCompletion', null)->setAllowedTypes('notBeforeCompletion', [\DateTimeInterface::class, 'null'])
            ->setDefault('notAfterCompletion', null)->setAllowedTypes('notAfterCompletion', [\DateTimeInterface::class, 'null'])
            ->setDefault('status', null)->setAllowedTypes('status', ['int', 'null'])
            ->setDefault('withAffiliated', null)->setAllowedTypes('withAffiliated', ['bool', 'null']);
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

    public function setRequesterEmail(?string $requesterEmail): self
    {
        $this->requesterEmail = $requesterEmail;

        return $this;
    }

    public function getRequesterEmail(): ?string
    {
        return $this->requesterEmail;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setNotBefore(?\DateTimeInterface $notBefore): self
    {
        $this->notBefore = $notBefore;

        return $this;
    }

    public function getNotBefore(): ?\DateTimeInterface
    {
        return $this->notBefore;
    }

    public function setNotAfter(?\DateTimeInterface $notAfter): self
    {
        $this->notAfter = $notAfter;

        return $this;
    }

    public function getNotAfter(): ?\DateTimeInterface
    {
        return $this->notAfter;
    }

    public function setStartRange(?int $startRange): self
    {
        $this->startRange = $startRange;

        return $this;
    }

    public function getStartRange(): ?int
    {
        return $this->startRange;
    }

    public function setStopRange(?int $stopRange): self
    {
        $this->stopRange = $stopRange;

        return $this;
    }

    public function getStopRange(): ?int
    {
        return $this->stopRange;
    }

    public function setSignerId(?string $signerId): self
    {
        $this->signerId = $signerId;

        return $this;
    }

    public function getSignerId(): ?string
    {
        return $this->signerId;
    }

    public function setNotBeforeCompletion(?\DateTimeInterface $notBeforeCompletion): self
    {
        $this->notBeforeCompletion = $notBeforeCompletion;

        return $this;
    }

    public function getNotBeforeCompletion(): ?\DateTimeInterface
    {
        return $this->notBeforeCompletion;
    }

    public function setNotAfterCompletion(?\DateTimeInterface $notAfterCompletion): self
    {
        $this->notAfterCompletion = $notAfterCompletion;

        return $this;
    }

    public function getNotAfterCompletion(): ?\DateTimeInterface
    {
        return $this->notAfterCompletion;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setWithAffiliated(?bool $withAffiliated): self
    {
        $this->withAffiliated = $withAffiliated;

        return $this;
    }

    public function getWithAffiliated(): ?bool
    {
        return $this->withAffiliated;
    }
}
