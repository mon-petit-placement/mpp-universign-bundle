<?php

namespace Mpp\UniversignBundle\Model;

class SignOptions
{
    /**
     * @var string|null
     */
    protected $profile;

    /**
     * @var SignatureField|null
     */
    protected $signatureField;

    /**
     * @var string|null
     */
    protected $reason;

    /**
     * @var string|null
     */
    protected $location;

    /**
     * @var string|null
     */
    protected $signatureFormat;

    /**
     * @var string|null
     */
    protected $language;

    /**
     * @var string|null
     */
    protected $patternName;

    /**
     * @param array $options
     *
     * @return self
     */
    public static function createFromArray(array $options): self
    {
        return (new self())
            ->setProfile($options['profile'] ?? null)
            ->setSignatureField($options['signatureField'] ?? null)
            ->setReason($options['reason'] ?? null)
            ->setLocation($options['location'] ?? null)
            ->setSignatureFormat($options['signatureFormat'] ?? null)
            ->setLanguage($options['language'] ?? null)
            ->setPatternName($options['patternName'] ?? null)
        ;
    }

    /**
     * @return string|null
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * @param string|null $profile
     * @return SignOptions
     */
    public function setProfile(?string $profile): SignOptions
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * @return SignatureField|null
     */
    public function getSignatureField(): ?SignatureField
    {
        return $this->signatureField;
    }

    /**
     * @param SignatureField|null $signatureField
     * @return SignOptions
     */
    public function setSignatureField(?SignatureField $signatureField): SignOptions
    {
        $this->signatureField = $signatureField;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param string|null $reason
     * @return SignOptions
     */
    public function setReason(?string $reason): SignOptions
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     * @return SignOptions
     */
    public function setLocation(?string $location): SignOptions
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSignatureFormat(): ?string
    {
        return $this->signatureFormat;
    }

    /**
     * @param string|null $signatureFormat
     * @return SignOptions
     */
    public function setSignatureFormat(?string $signatureFormat): SignOptions
    {
        $this->signatureFormat = $signatureFormat;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string|null $language
     * @return SignOptions
     */
    public function setLanguage(?string $language): SignOptions
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPatternName(): ?string
    {
        return $this->patternName;
    }

    /**
     * @param string|null $patternName
     * @return SignOptions
     */
    public function setPatternName(?string $patternName): SignOptions
    {
        $this->patternName = $patternName;
        return $this;
    }
}
