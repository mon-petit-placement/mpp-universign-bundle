<?php

namespace Mpp\UniversignBundle\Model;

use Laminas\XmlRpc\Value\Base64;
use PhpXmlRpc\Value;
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
     * @var Value[]
     */
    protected array $chain;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('subjectDN')->setAllowedTypes('subjectDN', ['string'])
            ->setRequired('serialNumber')->setAllowedTypes('serialNumber', ['string'])
            ->setRequired('chain')->setAllowedTypes('chain', ['array', Value::class])->setNormalizer('chain', function (Options $options, $value): array {
                if (null === $value) {
                    return [];
                }

                $result = [];
                foreach ($value as $item) {
                    $result[] = new Value(base64_encode($item), 'base64');
                }

                return $result;
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
            ->setSubjectDN($resolvedOptions['subjectDN'])
            ->setSerialNumber($resolvedOptions['serialNumber'])
            ->setChain($resolvedOptions['chain'])
        ;
    }

    /**
     * @return string
     */
    public function getSubjectDN(): string
    {
        return $this->subjectDN;
    }

    /**
     * @param string $subjectDN
     *
     * @return self
     */
    public function setSubjectDN(string $subjectDN): self
    {
        $this->subjectDN = $subjectDN;

        return $this;
    }

    /**
     * @return string
     */
    public function getSerialNumber(): string
    {
        return $this->serialNumber;
    }

    /**
     * @param string $serialNumber
     *
     * @return self
     */
    public function setSerialNumber(string $serialNumber): self
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * @return Value[]
     */
    public function getChain(): array
    {
        return $this->chain;
    }

    /**
     * @param Value[] $chain
     *
     * @return self
     */
    public function setChain(array $chain): self
    {
        $this->chain = $chain;

        return $this;
    }
}
