<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Flex\Options as FlexOptions;

class DocSignatureField extends SignatureField
{
    /**
     * @var string
     */
    protected $patternName;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var \Laminas\XmlRpc\Value\Base64
     */
    protected $image;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        parent::configureData($resolver);

        $resolver
            ->setDefault('patternName', null)->setAllowedTypes('patternName', ['string', 'null'])
            ->setDefault('label', null)->setAllowedTypes('label', ['string', 'null'])
            ->setDefault('image', null)->setAllowedTypes('image', ['string', 'null'])
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

        return (new self())
            ->setName($resolvedData['name'])
            ->setPage($resolvedData['page'])
            ->setX($resolvedData['x'])
            ->setY($resolvedData['y'])
            ->setSignerIndex($resolvedData['signerIndex'])
            ->setPatternName($resolvedData['patternName'])
            ->setLabel($resolvedData['label'])
            ->setImage($resolvedData['image'])
        ;
    }

    /**
     * @param string $patternName
     *
     * @return self
     */
    public function setPatternName(?string $patternName): self
    {
        $this->patternName = $patternName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPatternName():string
    {
        return $this->patternName;
    }

    /**
     * @param string $label
     *
     * @return self
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel():string
    {
        return $this->label;
    }

    /**
     * @param array $image
     *
     * @return self
     */
    public function setImage(?array $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \Laminas\XmlRpc\Value\Base64
     */
    public function getImage(): \Laminas\XmlRpc\Value\Base64
    {
        return $this->image;
    }
}