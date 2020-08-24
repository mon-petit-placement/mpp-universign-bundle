<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SepaThirdParty
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $address;
    /**
     * @var string
     */
    protected $postalCode;
    /**
     * @var string
     */
    protected $city;
    /**
     * @var string
     */
    protected $country;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('name')->setAllowedTypes('name', ['string'])
            ->setRequired('address')->setAllowedTypes('address', ['string'])
            ->setRequired('postalCode')->setAllowedTypes('postalCode', ['string'])
            ->setRequired('city')->setAllowedTypes('city', ['string'])
            ->setRequired('country')->setAllowedTypes('country', ['string'])
        ;
    }

    /**
     * @param array $data
     *
     * @return SepaThirdParty
     */
    public static function createFromArray(array $data): SepaThirdParty
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedData = $resolver->resolve($data);

        return (new SepaThirdParty())
            ->setName($resolvedData['name'])
            ->setAddress($resolvedData['address'])
            ->setPostalCode($resolvedData['postalCode'])
            ->setCity($resolvedData['city'])
            ->setCountry($resolvedData['country'])
        ;
    }

    /**
     * @param string|null $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $address
     *
     * @return self
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $postalCode
     *
     * @return self
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param string|null $city
     *
     * @return self
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $country
     *
     * @return self
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }
}