<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class RegistrationRequest
{
    const DOCTYPE__ID_CARD_FR = 'id_card_fr';
    const DOCTYPE_PASSPORT = 'passport_eu';
    const DOCTYPE_TITLE_STAY = 'titre_sejour';


    /**
     * @var array
     */
    protected $documents;
    /**
     * @var string
     */
    protected $type;

    public function __construct()
    {
        $this->documents = [];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined('documents')->setAllowedTypes('documents', ['array'])
            ->setDefined('type')->setAllowedTypes('type', ['string'])
        ;
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
        $idDocument = new RegistrationRequest();
            $idDocument
                ->setDocuments(array_key_exists('documents', $resolvedData) ? $resolvedData['documents'] : array())
                ->setType(array_key_exists('type', $resolvedData) ? $resolvedData['type'] : null)
            ;
        return $idDocument;
    }

    /**
     * @param array|null $documents
     *
     * @return self
     */
    public function setDocuments(?array $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getDocuments(): ?array
    {
        return $this->documents;
    }

    /**
     * @param string|null $type
     *
     * @return self
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}