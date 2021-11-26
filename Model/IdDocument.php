<?php

namespace Mpp\UniversignBundle\Model;

use Mpp\UniversignBundle\Utils\StringUtils;
use PhpXmlRpc\Value;
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
    public const TYPE_IDENTITY_CARD = 0;
    public const TYPE_PASSPORT = 1;
    public const TYPE_RESIDENCE_PERMIT = 2;
    public const TYPE_EUROPEAN_DRIVER_LICENSE = 3;

    /**
     * @var Value[]
     */
    protected array $photos;

    /**
     * @var int
     */
    protected int $type;

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
                    if ($value instanceof Value || null === $value) {
                        $list[] = $value;
                        continue;
                    }

                    if (file_exists($value)) {
                        $list[] = new Value(base64_encode(file_get_contents($value)), 'base64');
                        continue;
                    }

                    if (!StringUtils::isBase64($value)) {
                        $value = base64_encode($value);
                    }

                    $list[] = new Value($value, 'base64');
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
     * @return Value[]
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
