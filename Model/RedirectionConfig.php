<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedirectionConfig
{
    /**
     * @var string
     */
    protected $URL;
    /**
     * @var string
     */
    protected $displayName;


    /**
     * @param OptionsResolver
     */
    public static function configureData(OptionsResolver $resolver) {
        $resolver
            ->setDefault('URL', null)->setAllowedTypes('URL', ['string', 'null'])
            ->setDefault('displayName', null)->setAllowedTypes('displayName', ['string', 'null'])
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

        return (new RedirectionConfig())
            ->setUrl($resolvedData['URL'])
            ->setDisplayName($resolvedData['displayName'])
        ;
    }

    /**
     * @param string|null $url
     *
     * @return self
     */
    public function setUrl(?string $url): self
    {
        $this->URL = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->URL;
    }

    /**
     * @param string|null $displayName
     *
     * @return self
     */
    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

}