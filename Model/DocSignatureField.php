<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Flex\Options as FlexOptions;

class DocSignatureField
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * @var int
     */
    protected $signerIndex;

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
        $resolver
            ->setDefined('name')->setAllowedTypes('name', ['string'])
            ->setDefined('page')->setAllowedTypes('page', ['int'])
            ->setDefined('x')->setAllowedTypes('x', ['int'])
            ->setDefined('y')->setAllowedTypes('y', ['int'])
            ->setRequired('signerIndex')->setAllowedTypes('signerIndex', ['int'])
            ->setDefined('patternName')->setAllowedTypes('patternName', ['string'])
            ->setDefined('label')->setAllowedTypes('label', ['string'])
            ->setDefined('image')->setAllowedTypes('image', ['string'])

        ;
    }

    /**
     * @param array $data
     *
     * @return DocSignatureField
     */
    public static function createFromArray(array $data): DocSignatureField
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedData = $resolver->resolve($data);
        dump($resolvedData);
        $docSignatureField = new DocSignatureField();

        $docSignatureField
            ->setName(array_key_exists('name', $resolvedData) ? $resolvedData['name'] : null)
            ->setPage(array_key_exists('page', $resolvedData) ? $resolvedData['page'] : null)
            ->setX(array_key_exists('x', $resolvedData) ? $resolvedData['x'] : null)
            ->setY(array_key_exists('y', $resolvedData) ? $resolvedData['y'] : null)
            ->setSignerIndex(array_key_exists('signerIndex', $resolvedData) ? $resolvedData['signerIndex'] : null)
            ->setPatternName(array_key_exists('patternName', $resolvedData) ? $resolvedData['patternName'] : null)
            ->setLabel(array_key_exists('label', $resolvedData) ? $resolvedData['label'] : null)
            ->setImage(array_key_exists('image', $resolvedData) ? $resolvedData['image'] : null)
        ;

        return $docSignatureField;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name = null): self
    {
        $this-> name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $page
     *
     * @return self
     */
    public function setPage(int $page = null): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $x;
     *
     * @return self
     */
    public function setX(int $x = null): self
    {
        $this->x = $x;

        return $this;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $y;
     *
     * @return self
     */
    public function setY(int $y = null): self
    {
        $this->y = $y;

        return $this;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $signerIndex
     *
     * @return self
     */
    public function setSignerIndex(int $signerIndex = null): self
    {
        $this->signerIndex = $signerIndex;

        return $this;
    }

    /**
     * @return int
     */
    public function getSignerIndex(): int
    {
        return $this->signerIndex;
    }

    /**
     * @param string $patternName
     *
     * @return self
     */
    public function setPatternName(string $patternName = null): self
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
    public function setLabel(string $label): self
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
    public function setImage(array $image): self
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