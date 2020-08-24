<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignatureField
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
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('name', null)->setAllowedTypes('name', ['string', 'null'])
            ->setDefault('page', 1)->setAllowedTypes('page', ['int'])
            ->setDefault('x', null)->setAllowedTypes('x', ['int', 'null'])
            ->setDefault('y', null)->setAllowedTypes('y', ['int', 'null'])
            ->setRequired('signerIndex', null)->setAllowedTypes('signerIndex', ['int', 'null'])
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
        ;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this-> name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param int $page
     *
     * @return self
     */
    public function setPage(?int $page): self
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
    public function setX(?int $x): self
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
    public function setY(?int $y): self
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
    public function setSignerIndex(?int $signerIndex): self
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
}