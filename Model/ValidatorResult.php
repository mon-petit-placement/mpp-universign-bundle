<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidatorResult
{
    public const STATUS_PENDING = 0;
    public const STATUS_VALID = 1;
    public const STATUS_INVALID = 2;

    protected string $id;

    protected int $status;

    protected ?int $reason;

    protected ?string $reasonMessage;

    protected ?array $result;

    public function __construct(string $id, int $status)
    {
        $this->id = $id;
        $this->status = $status;
        $this->reason = null;
        $this->reasonMessage = null;
        $this->result = null;
    }

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('id')->setAllowedTypes('id', ['string'])
            ->setRequired('status')->setAllowedValues('status', [self::STATUS_PENDING, self::STATUS_INVALID, self::STATUS_VALID])
            ->setDefault('reason', null)->setAllowedTypes('reason', ['int', 'null'])
            ->setDefault('reasonMessage', null)->setAllowedTypes('reasonMessage', ['string', 'null'])
            ->setDefault('result', null)->setAllowedTypes('result', ['array', 'null'])
            ->setDefault('externalId', null)->setAllowedTypes('externalId', ['string', 'null'])
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

        return (new self(
            $resolvedOptions['id'],
            $resolvedOptions['status'],
        ))
            ->setReason($resolvedOptions['reason'])
            ->setReasonMessage($resolvedOptions['reasonMessage'])
            ->setResult($resolvedOptions['result'])
        ;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getReason(): ?int
    {
        return $this->reason;
    }

    public function setReason(?int $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getReasonMessage(): ?string
    {
        return $this->reasonMessage;
    }

    public function setReasonMessage(?string $reasonMessage): self
    {
        $this->reasonMessage = $reasonMessage;

        return $this;
    }

    public function getResult(): ?array
    {
        return $this->result;
    }

    public function setResult(?array $result): self
    {
        $this->result = $result;

        return $this;
    }
}
