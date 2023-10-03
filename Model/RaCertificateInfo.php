<?php

namespace Mpp\UniversignBundle\Model;

use Mpp\UniversignBundle\Model\XmlRpc\Base64;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaCertificateInfo
{
    protected string $subjectDN;

    protected string $serialNumber;

    /**
     * @var Base64[]
     */
    protected array $chain;

    /**
     * @param Base64[] $chain
     */
    public function __construct(string $subjectDN, string $serialNumber, array $chain)
    {
        $this->subjectDN = $subjectDN;
        $this->serialNumber = $serialNumber;
        $this->chain = $chain;
    }

    /**
     * @throws \UnexpectedValueException
     */
    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired('subjectDN')->setAllowedTypes('subjectDN', ['string'])
            ->setRequired('serialNumber')->setAllowedTypes('serialNumber', ['string'])
            ->setRequired('chain')->setAllowedTypes('chain', ['array'])->setNormalizer('chain', function (Options $options, $value): array {
                $result = [];
                foreach ($value as $item) {
                    if (!is_string($item)) {
                        throw new \UnexpectedValueException(
                            'Type ' . gettype($item) . ' is not allowed in chain array'
                        );
                    }
                    $result[] = new Base64($item);
                }

                return $result;
            })
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
            $resolvedOptions['subjectDN'],
            $resolvedOptions['serialNumber'],
            $resolvedOptions['chain'],
        ));
    }

    public function getSubjectDN(): string
    {
        return $this->subjectDN;
    }

    public function setSubjectDN(string $subjectDN): self
    {
        $this->subjectDN = $subjectDN;

        return $this;
    }

    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * @return Base64[]
     */
    public function getChain(): array
    {
        return $this->chain;
    }

    /**
     * @param Base64[] $chain
     */
    public function setChain(array $chain): self
    {
        $this->chain = $chain;

        return $this;
    }
}
