<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
     * @param array $documents
     *
     * @return self
     */
    public function setDocuments(array $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    /**
     * @return array
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type = null): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}