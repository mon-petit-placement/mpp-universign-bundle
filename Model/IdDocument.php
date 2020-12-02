<?php

namespace Mpp\UniversignBundle\Model;

use Laminas\XmlRpc\Value\Base64;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdDocument
{
    const TYPE_IDENTITY_CARD = 0;
    const TYPE_PASSPORT = 1;
    const TYPE_RESIDENCE_PERMIT = 2;
    const TYPE_EUROPEAN_DRIVER_LICENSE = 3;

    /**
     * @var Base64[]
     */
    protected $photos;

    /**
     * @var int
     */
    protected $type;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('type')->setAllowedValues('type', [self::TYPE_EUROPEAN_DRIVER_LICENSE, self::TYPE_RESIDENCE_PERMIT, self::TYPE_PASSPORT, self::TYPE_IDENTITY_CARD])
            ->setRequired('photos')->setAllowedTypes('photos', ['array'])->setNormalizer('photos', function (Options $options, $array): ?array {
                $list = [];
                foreach ($array as $value) {
                    if ($value instanceof Base64) {
                        $list[] = $value;
                        continue;
                    }

                    if (null === $value || !file_exists($value)) {
                        continue;
                    }

                    $file = file_get_contents($value);
                    $list[] = new \Laminas\XmlRpc\Value\Base64($file);
                }

                return $list;
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
    public static function createFromArray(array $options): IdDocument
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return (new self())
            ->setPhotos($resolvedOptions['photos'])
            ->setType($resolvedOptions['type'])
        ;
    }

    /**
     * @return Base64[]
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @param array $photos
     *
     * @return self
     */
    public function setPhotos(array $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return self
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
