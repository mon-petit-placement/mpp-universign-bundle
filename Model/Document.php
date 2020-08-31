<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Document
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $documentType;

    /**
     * @var \Laminas\XmlRpc\Value\Base64
     */
    protected $content;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var array<DocSignatureField>
     */
    protected $signatureFields;

    /**
     * @var array
     */
    protected $checkBoxTexts;

    /**
     * @var array
     */
    protected $metaData;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var SepaData
     */
    protected $sepaData;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('id', null)->setAllowedTypes('id', ['string', 'null'])
            ->setDefault('documentType', null)->setAllowedTypes('documentType', ['string', 'null'])
            ->setDefault('content', null)->setAllowedTypes('content', ['string', 'null'])->setNormalizer('content', function (Options $option, $value): ?\Laminas\XmlRpc\Value\Base64 {
                if (null === $value || !file_exists($value)) {
                    return null;
                }

                $file = file_get_contents($value);
                $b64 = new \Laminas\XmlRpc\Value\Base64($file);

                return $b64;
            })
            ->setDefault('url', null)->setAllowedTypes('url', ['string', 'null'])
            ->setDefault('fileName', null)->setAllowedTypes('fileName', ['string', 'null'])
            ->setDefault('signatureFields', null)->setAllowedTypes('signatureFields', ['array', 'null'])->setNormalizer('signatureFields', function (Options $option, $values): array {
                if (null === $values) {
                    return [];
                }

                $signatureFields = [];
                foreach ($values as $value) {
                    $signatureFields[] = DocSignatureField::createFromArray($value);
                }

                return $signatureFields;
            })
            ->setDefault('checkBoxTexts', null)->setAllowedTypes('checkBoxTexts', ['array', 'null'])
            ->setDefault('metaData', null)->setAllowedTypes('metaData', ['array', 'null'])
            ->setDefault('title', null)->setAllowedTypes('title', ['string', 'null'])
            ->setDefault('SEPAData', null)->setAllowedTypes('SEPAData', ['array', 'null'])->setNormalizer('SEPAData', function (Options $option, $value): ?SepaData {
                if (null === $value) {
                    return null;
                }

                return SepaData::createFromArray($value);
            })
        ;
    }

    /**
     * @param array $documentData
     *
     * @return Document
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
    public static function createFromArray(array $data): Document
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedData = $resolver->resolve($data);

        return (new self())
            ->setId($resolvedData['id'])
            ->setDocumentType($resolvedData['documentType'])
            ->setContent($resolvedData['content'])
            ->setUrl($resolvedData['url'])
            ->setFileName($resolvedData['fileName'])
            ->setSignatureFields($resolvedData['signatureFields'])
            ->setCheckBoxTexts($resolvedData['checkBoxTexts'])
            ->setMetaData($resolvedData['metaData'])
            ->setTitle($resolvedData['title'])
            ->setSepaData($resolvedData['SEPAData'])
        ;
    }

    /**
     * @param int|null $id
     *
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string|null $documentType
     *
     * @return self
     */
    public function setDocumentType(?string $documentType): self
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    /**
     * @param \Laminas\XmlRpc\Value\Base64|null $content
     *
     * @return self
     */
    public function setContent(?\Laminas\XmlRpc\Value\Base64 $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return \Laminas\XmlRpc\Value\Base64|null
     */
    public function getContent(): ?\Laminas\XmlRpc\Value\Base64
    {
        return $this->content;
    }

    /**
     * @param string|null $url
     *
     * @return self
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $fileName
     *
     * @return self
     */
    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param SignatureField $signatureField
     *
     * @return self
     */
    public function addSignatureField(SignatureField $signatureField): self
    {
        $this->signatureFields[] = $signatureField;

        return $this;
    }

    /**
     * @param array<SignatureField>|null $signatureFields
     *
     * @return self
     */
    public function setSignatureFields(?array $signatureFields): self
    {
        $this->signatureFields = $signatureFields;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getSignatureFields(): ?array
    {
        return $this->signatureFields;
    }

    /**
     * @param array|null $checkBoxTexts
     *
     * @return self
     */
    public function setCheckBoxTexts(?array $checkBoxTexts): self
    {
        $this->checkBoxTexts = $checkBoxTexts;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCheckBoxTexts(): ?array
    {
        return $this->checkBoxTexts;
    }

    /**
     * @param array|null $metaData
     *
     * @return self
     */
    public function setMetaData(?array $metaData): self
    {
        $this->metaData = $metaData;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getMetaData(): ?array
    {
        return $this->metaData;
    }

    /**
     * @param string|null $title
     *
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param SepaData|null $sepaData
     *
     * @return self
     */
    public function setSepaData(?SepaData $sepaData): self
    {
        $this->sepaData = $sepaData;

        return $this;
    }

    /**
     * @return SepaData|null
     */
    public function getSepaData(): ?SepaData
    {
        return $this->sepaData;
    }
}
