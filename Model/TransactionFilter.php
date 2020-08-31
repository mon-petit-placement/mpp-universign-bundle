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

class TransactionFilter
{
    /**
     * @var string
     */
    protected $requesterEmail;

    /**
     * @var string
     */
    protected $profile;

    /**
     * @var \Laminas\XmlRpc\Value\DateTime
     */
    protected $notBefore;

    /**
     * @var \Laminas\XmlRpc\Value\DateTime
     */
    protected $notAfter;

    /**
     * @var int
     */
    protected $startRange;

    /**
     * @var int
     */
    protected $stopRange;

    /**
     * @var string
     */
    protected $signerId;

    /**
     * @var \Laminas\XmlRpc\Value\DateTime
     */
    protected $notBeforeCompletion;

    /**
     * @var \Laminas\XmlRpc\Value\DateTime
     */
    protected $notAfterCompletion;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var bool
     */
    protected $withAffiliated;

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
            ->setDefault('notBefore', null)->setAllowedTypes('notBefore', ['DateTime', 'null'])->setNormalizer('notBefore', function (Options $option, $value) {
                if (null === $value) {
                    return null;
                }
                $value = $value->format('Ymd\TH:i:s');
                $date = new \Laminas\XmlRpc\Value\DateTime($value);

                return $date;
            })
            ->setDefault('notAfter', null)->setAllowedTypes('notAfter', ['DateTime', 'null'])->setNormalizer('notAfter', function (Options $option, $value) {
                if (null === $value) {
                    return null;
                }
                $value = $value->format('Ymd\TH:i:s');
                $date = new \Laminas\XmlRpc\Value\DateTime($value);

                return $date;
            })
            ->setDefault('startRange', null)->setAllowedTypes('startRange', ['int', 'null'])
            ->setDefault('stopRange', null)->setAllowedTypes('stopRange', ['int', 'null'])
            ->setDefault('signerId', null)->setAllowedTypes('signerId', ['string', 'null'])
            ->setDefault('notBeforeCompletion', null)->setAllowedTypes('notBeforeCompletion', ['DateTime', 'null'])->setNormalizer('notBeforeCompletion', function (Options $option, $value) {
                if (null === $value) {
                    return null;
                }
                $value = $value->format('Ymd\TH:i:s');
                $date = new \Laminas\XmlRpc\Value\DateTime($value);

                return $date;
            })
            ->setDefault('notAfterCompletion', null)->setAllowedTypes('notAfterCompletion', ['DateTime', 'null'])->setNormalizer('notAfterCompletion', function (Options $option, $value) {
                if (null === $value) {
                    return null;
                }
                $value = $value->format('Ymd\TH:i:s');
                $date = new \Laminas\XmlRpc\Value\DateTime($value);

                return $date;
            })
            ->setDefault('status', null)->setAllowedTypes('status', ['int', 'null'])
            ->setDefault('withAffiliated', null)->setAllowedTypes('withAffiliated', ['bool', 'null']);
    }

    /**
     * @param array $data
     *
     * @return self
     *
     * @throws UndefinedOptionsException If an option name is undefined
     * @throws InvalidOptionsException   If an option doesn't fulfill the
     *                                   specified validation rules
     * @throws MissingOptionsException   If a required option is missing
     * @throws OptionDefinitionException If there is a cyclic dependency between
     *                                   lazy options and/or normalizers
     * @throws NoSuchOptionException     If a lazy option reads an unavailable option
     * @throws AccessException           If called from a lazy option or normalizer
     */
    public static function createFromArray(array $data): self
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedData = $resolver->resolve($data);

        return(new self())
            ->setRequesterEmail($resolvedData['requesterEmail'])
            ->setProfile($resolvedData['profile'])
            ->setNotBefore($resolvedData['notBefore'])
            ->setNotAfter($resolvedData['notAfter'])
            ->setStartRange($resolvedData['startRange'])
            ->setStopRange($resolvedData['stopRange'])
            ->setSignerId($resolvedData['signerId'])
            ->setNotBeforeCompletion($resolvedData['notBeforeCompletion'])
            ->setNotAfterCompletion($resolvedData['notAfterCompletion'])
            ->setStatus($resolvedData['status'])
            ->setWithAffiliated($resolvedData['withAffiliated'])
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
     * @param \Laminas\XmlRpc\Value\DateTime|null $notBefore
     *
     * @return self
     */
    public function setNotBefore(?\Laminas\XmlRpc\Value\DateTime $notBefore): self
    {
        $this->notBefore = $notBefore;

        return $this;
    }

    /**
     * @return \Laminas\XmlRpc\Value\DateTime|null
     */
    public function getNotBefore(): ?\Laminas\XmlRpc\Value\DateTime
    {
        return $this->notBefore;
    }

    /**
     * @param \Laminas\XmlRpc\Value\DateTime|null $notAfter
     *
     * @return self
     */
    public function setNotAfter(?\Laminas\XmlRpc\Value\DateTime $notAfter): self
    {
        $this->notAfter = $notAfter;

        return $this;
    }

    /**
     * @return \Laminas\XmlRpc\Value\DateTime|null
     */
    public function getNotAfter(): ?\Laminas\XmlRpc\Value\DateTime
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
     * @param \Laminas\XmlRpc\Value\DateTime|null $notBeforeCompletion
     *
     * @return self
     */
    public function setNotBeforeCompletion(?\Laminas\XmlRpc\Value\DateTime $notBeforeCompletion): self
    {
        $this->notBeforeCompletion = $notBeforeCompletion;

        return $this;
    }

    /**
     * @return \Laminas\XmlRpc\Value\DateTime|null
     */
    public function getNotBeforeCompletion(): ?\Laminas\XmlRpc\Value\DateTime
    {
        return $this->notBeforeCompletion;
    }

    /**
     * @param \Laminas\XmlRpc\Value\DateTime|null $notAfterCompletion
     *
     * @return self
     */
    public function setNotAfterCompletion(?\Laminas\XmlRpc\Value\DateTime $notAfterCompletion): self
    {
        $this->notAfterCompletion = $notAfterCompletion;

        return $this;
    }

    /**
     * @return \Laminas\XmlRpc\Value\DateTime|null
     */
    public function getNotAfterCompletion(): ?\Laminas\XmlRpc\Value\DateTime
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
