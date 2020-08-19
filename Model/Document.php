<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Document
{

    /**
     * @var long
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
     * @var DocSignatureField
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
            ->setDefined('title')->setAllowedTypes('title', ['string'])
            ->setDefined('documentType')->setAllowedTypes('documentType', ['string'])
            ->setDefined('content')->setAllowedTypes('content', ['string'])
            ->setDefined('checkBoxTexts')->setAllowedTypes('checkBoxTexts', ['array'])
            ->setDefined('url')->setAllowedTypes('url', ['string'])
            ->setDefined('fileName')->setAllowedTypes('fileName', ['string'])
            ->setDefined('DocSignatureField')->setAllowedTypes('DocSignatureField', ['array'])
            ->setDefined('SEPAData')->setAllowedTypes('SEPAData', ['array'])
        ;
    }

    /**
     * @param array $documentData
     *
     * @return Document
     */
    public static function createFromArray(array $documentData): Document
    {
        $document = new Document();

        //todo use getter setter to transform the array to a obj document
        return $document;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param long $documentType
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;
    }

    /**
     * @return string
     */
    public function getDocumentType(): string
    {
        return $this->documentType;
    }

    /**
     * @param \Laminas\XmlRpc\Value\Base64 $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return \Laminas\XmlRpc\Value\Base64
     */
    public function getContent(): \Laminas\XmlRpc\Value\Base64
    {
        return $this->content;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param array $signatureFields
     */
    public function setSignatureFields(array $signatureFields)
    {
        $this->signatureFields = DocSignatureField::createFromArray($signatureFields);
    }

    /**
     * @return DocSignatureField
     */
    public function getSignatureFields(): DocSignatureField
    {
        return $this->signatureFields;
    }

    /**
     * @param array $checkBoxTexts
     */
    public function setCheckBoxTexts($checkBoxTexts)
    {
        $this->checkBoxTexts = $checkBoxTexts;
    }

    /**
     * @return array
     */
    public function getCheckBoxTexts(): array
    {
        return $this->checkBoxTexts;
    }

    /**
     * @param array $metaData
     */
    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param array $sepaData
     */
    public function setSepaData($sepaData)
    {
        $this->sepaData = SepaData::createFromArray($sepaData);
    }

    /**
     * @return SepaData
     */
    public function getSepaData(): SepaData
    {
        return $this->sepaData;
    }

}
