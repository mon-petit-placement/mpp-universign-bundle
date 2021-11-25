<?php

namespace Mpp\UniversignBundle\Model;

use PhpXmlRpc\Value;
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
    protected int $id;

    protected string $documentType;

    protected Value $content;

    protected string $url;

    protected string $fileName;

    protected array $signatureFields;

    protected array $checkBoxTexts;

    protected array $metaData;

    protected string $title;

    protected SepaData $sepaData;

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('id', null)->setAllowedTypes('id', ['null', 'string'])
            ->setDefault('documentType', 'pdf')->setAllowedValues('documentType', ['pdf', 'pdf-for-presentation', 'pdf-optional', 'sepa'])
            ->setDefault('content', null)->setAllowedTypes('content', ['null', 'string', Value::class])->setNormalizer('content', function (Options $options, $value): ?Value {
                if ($value instanceof Value || null === $value) {
                    return $value;
                }

                if (file_exists($value)) {
                    return new Value(base64_encode(file_get_contents($value)), 'base64');
                }

                return new Value(base64_encode($value), 'base64');
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
            ->setDefault('checkBoxTexts', null)->setAllowedTypes('checkBoxTexts', ['null', 'array'])->setNormalizer('checkBoxTexts',  function (Options $options, $value) {
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
     * @param Value|null $content
     *
     * @return self
     */
    public function setContent(?Value $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Value|null
     */
    public function getContent(): ?Value
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
