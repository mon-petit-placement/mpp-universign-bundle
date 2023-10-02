<?php

namespace Mpp\UniversignBundle\Model;

use Mpp\UniversignBundle\Model\XmlRpc\Base64;
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
    protected ?int $id;

    protected string $documentType;

    protected ?Base64 $content;

    protected ?string $url;

    protected ?string $fileName;

    /**
     * @var array<DocSignatureField>
     */
    protected array $signatureFields;

    protected ?array $checkBoxTexts;

    protected ?array $metaData;

    protected ?string $title;

    protected ?SepaData $sepaData;

    public function __construct()
    {
        $this->id = null;
        $this->documentType = 'pdf';
        $this->content = null;
        $this->url = null;
        $this->fileName = null;
        $this->signatureFields = null;
        $this->checkBoxTexts = null;
        $this->metaData = null;
        $this->title = null;
        $this->sepaData = null;
    }

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('id', null)->setAllowedTypes('id', ['null', 'string'])
            ->setDefault('documentType', 'pdf')->setAllowedValues('documentType', ['pdf', 'pdf-for-presentation', 'pdf-optional', 'sepa'])
            ->setDefault('content', null)->setAllowedTypes('content', ['null', 'string', Base64::class])->setNormalizer('content', function (Options $options, $value): ?Base64 {
                if (null === $value || $value instanceof Base64) {
                    return $value;
                }

                return new Base64($value);
            })
            ->setDefault('url', null)->setAllowedTypes('url', ['null', 'string'])
            ->setDefault('fileName', null)->setAllowedTypes('fileName', ['null', 'string'])
            ->setDefault('signatureFields', null)->setAllowedTypes('signatureFields', ['array', 'null'])->setNormalizer('signatureFields', function (Options $options, $values): array {
                if (null === $values) {
                    return [];
                }

                $signatureFields = [];
                foreach ($values as $value) {
                    $signatureFields[] = DocSignatureField::createFromArray($value);
                }

                return $signatureFields;
            })
            ->setDefault('checkBoxTexts', null)->setAllowedTypes('checkBoxTexts', ['null', 'array'])->setNormalizer('checkBoxTexts', function (Options $options, $value) {
                if ('pdf-for-presentation' === $options['documentType']) {
                    return null;
                }

                return $value;
            })
            ->setDefault('metaData', null)->setAllowedTypes('metaData', ['null', 'array'])
            ->setDefault('title', null)->setAllowedTypes('title', ['null', 'string'])
            ->setDefault('SEPAData', null)->setAllowedTypes('SEPAData', ['null', 'array', SepaData::class])->setNormalizer('SEPAData', function (Options $options, $value): ?SepaData {
                if (null === $value) {
                    return null;
                }

                if ($value instanceof SepaData) {
                    return $value;
                }

                return SepaData::createFromArray($value);
            })
        ;
    }

    /**
     * @throws UndefinedOptionsException If an option name is undefined
     * @throws InvalidOptionsException   If an option doesn't fulfill the language specified validation rules
     * @throws MissingOptionsException   If a required option is missing
     * @throws OptionDefinitionException If there is a cyclic dependency between lazy options and/or normalizers
     * @throws NoSuchOptionException     If a lazy option reads an unavailable option
     * @throws AccessException           If called from a lazy option or normalizer
     */
    public static function createFromArray(array $options): Document
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return (new self())
            ->setId($resolvedOptions['id'])
            ->setDocumentType($resolvedOptions['documentType'])
            ->setContent($resolvedOptions['content'])
            ->setUrl($resolvedOptions['url'])
            ->setFileName($resolvedOptions['fileName'])
            ->setSignatureFields($resolvedOptions['signatureFields'])
            ->setCheckBoxTexts($resolvedOptions['checkBoxTexts'])
            ->setMetaData($resolvedOptions['metaData'])
            ->setTitle($resolvedOptions['title'])
            ->setSepaData($resolvedOptions['SEPAData'])
        ;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setDocumentType(string $documentType): self
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    public function setContent(?Base64 $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): ?Base64
    {
        return $this->content;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function addSignatureField(SignatureField $signatureField): self
    {
        $this->signatureFields[] = $signatureField;

        return $this;
    }

    public function setSignatureFields(array $signatureFields): self
    {
        $this->signatureFields = $signatureFields;

        return $this;
    }

    public function getSignatureFields(): array
    {
        return $this->signatureFields;
    }

    public function setCheckBoxTexts(?array $checkBoxTexts): self
    {
        $this->checkBoxTexts = $checkBoxTexts;

        return $this;
    }

    public function getCheckBoxTexts(): ?array
    {
        return $this->checkBoxTexts;
    }

    public function setMetaData(?array $metaData): self
    {
        $this->metaData = $metaData;

        return $this;
    }

    public function getMetaData(): ?array
    {
        return $this->metaData;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setSepaData(?SepaData $sepaData): self
    {
        $this->sepaData = $sepaData;

        return $this;
    }

    public function getSepaData(): ?SepaData
    {
        return $this->sepaData;
    }
}
