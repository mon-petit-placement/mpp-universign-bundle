<?php

namespace Mpp\UniversignBundle\Model;

class Transaction
{
    /**
     * @var array<Signer> $signers
     */
    protected $signers;

    /**
     * @var array<Document> $documents
     */
    protected $documents;

    public function __construct()
    {
        $this->signers = [];
        $this->documents = [];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = (array)$this;

        return self::removeEmptyValues($data);
    }

    public static function removeEmptyValues(array &$array)
    {
        foreach ($array as $key => &$value) {
            if (is_object($value) &&
                !($value instanceof \Laminas\XmlRpc\Value\DateTime) &&
                !($value instanceof \Laminas\XmlRpc\Value\Base64)
            ) {
                $value = (array)$value;
            }
            if (is_array($value)) {
                $value = self::removeEmptyValues($value);
            }
            if (null == $value) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * @param array $signerData
     *
     * @return Transaction
     */
    public function addSigner(array $signerData): self
    {
        $this->signers[] = Signer::createFromArray($signerData);

        return $this;
    }

    /**
     * @return array<Signer>
     */
    public function getSigners(): array
    {
        return $this->signers;
    }

    /**
     * @param array $documentData
     *
     * @return Transaction
     */
    public function addDocument(array $documentData): self
    {
        $this->documents[] = Document::createFromArray($documentData);

        return $this;
    }

    /**
     * @return arrat<Document>
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }
}
