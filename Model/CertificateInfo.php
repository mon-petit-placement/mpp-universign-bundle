<?php

namespace Mpp\UniversignBundle\Model;

class CertificateInfo
{
    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $issuer;

    /**
     * @var string
     */
    protected $serial;

    /**
     * @param array $options
     *
     * @return self
     */
    public static function createFromArray(array $options): self
    {
        return (new self())
            ->setSubject($options['subject'] ?? null)
            ->setIssuer($options['issuer'] ?? null)
            ->setSerial($options['serial'] ?? null)
        ;
    }

    /**
     * @param string|null $subject
     *
     * @return self
     */
    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string|null $issuer
     *
     * @return self
     */
    public function setIssuer(?string $issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    /**
     * @param string|null $serial
     *
     * @return self
     */
    public function setSerial(?string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSerial(): ?string
    {
        return $this->serial;
    }
}
