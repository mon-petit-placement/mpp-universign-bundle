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

class ValidatorResult
{
    const STATUS_PENDING = 0;
    const STATUS_VALID = 1;
    const STATUS_INVALID = 2;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var null|int
     */
    protected $reason;

    /**
     * @var null|string
     */
    protected $reasonMessage;

    /**
     * @var null|array
     */
    protected $result;

    public function __construct()
    {
        $this->reason = null;
        $this->reasonMessage = null;
        $this->result = null;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('id')->setAllowedTypes('id', ['string'])
            ->setRequired('status')->setAllowedValues('status', [self::STATUS_PENDING, self::STATUS_INVALID, self::STATUS_VALID])
            ->setDefault('reason', null)->setAllowedTypes('reason', ['int', 'null'])
            ->setDefault('reasonMessage', null)->setAllowedTypes('reasonMessage', ['string', 'null'])
            ->setDefault('result', false)->setAllowedTypes('result', ['array', 'null'])
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
            ->setId($resolvedOptions['id'])
            ->setStatus($resolvedOptions['status'])
            ->setReason($resolvedOptions['reason'])
            ->setReasonMessage($resolvedOptions['reasonMessage'])
            ->setResult($resolvedOptions['result'])
        ;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getReason(): ?int
    {
        return $this->reason;
    }

    /**
     * @param int|null $reason
     *
     * @return self
     */
    public function setReason(?int $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReasonMessage(): ?string
    {
        return $this->reasonMessage;
    }

    /**
     * @param string|null $reasonMessage
     *
     * @return self
     */
    public function setReasonMessage(?string $reasonMessage): self
    {
        $this->reasonMessage = $reasonMessage;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getResult(): ?array
    {
        return $this->result;
    }

    /**
     * @param array|null $result
     *
     * @return self
     */
    public function setResult(?array $result): self
    {
        $this->result = $result;

        return $this;
    }
}
