<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionRequest
{

    /**
     * @var string
     */
    protected $profile;

    /**
     * @var string
     */
    protected $customId;

    /**
     * @var array<Signer> $signers
     */
    protected $signers;

    /**
     * @var array<Document> $documents
     */
    protected $documents;

    /**
     * @var bool
     */
    protected $mustContactFirstSigner;

    /**
     * @var bool
     */
    protected $finalDocSent;

    /**
     * @var bool
     */
    protected $finalDocRequesterSent;

    /**
     * @var bool
     */
    protected $finalDocObserverSent;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $certificateType;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var int
     */
    protected $handwrittenSignatureMode;

    /**
     * @var string
     */
    protected $chainingMode;

    /**
     * @var array
     */
    protected $finalDocCCeMails;

    /**
     * @var RedirectionConfig
     */
    protected $autoValidationRedirection;

    /**
     * @var string
     */
    protected $redirectPolicy;

    /**
     * @var int
     */
    protected $redirectWait;

    /**
     * @var bool
     */
    protected $autoSendAgreements;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string
     */
    protected $registrationCallbackURL;

    /**
     * @var RedirectionConfig
     */
    protected $sucessRedirection;

    /**
     * @var RedirectionConfig
     */
    protected $failRedirection;

    /**
     * @var RedirectionConfig
     */
    protected $cancelRedirection;

    /**
     * @var string
     */
    protected $invitationMessage;

    public function __construct()
    {
        $this->signers = [];
        $this->documents = [];
    }

    /**
     * @param string|null $profile
     *
     * @return self
     */
    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * @param string|null $customId
     *
     * @return self
     */
    public function setCustomId(?string $customId): self
    {
        $this->customId = $customId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    /**
     * @param array $signerData
     *
     * @return self
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
     * @param string $name
     * @param array $documentData
     *
     * @return self
     */
    public function addDocument(string $name, array $documentData): self
    {
        $this->documents[$name] = Document::createFromArray($documentData);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Document
     */
    public function getDocument(string $name): Document
    {
        return $this->documents[$name];
    }

    /**
     * @return array<Document>
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * @param bool|null $mustContactFirstSigner
     *
     * @return self
     */
    public function setMustContactFirstSigner(?bool $mustContactFirstSigner): self
    {
        $this->mustContactFirstSigner = $mustContactFirstSigner;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getMustContactFirstSigner(): ?bool
    {
        return $this->mustContactFirstSigner;
    }

    /**
     * @param bool|null $finalDocSent
     *
     * @return self
     */
    public function setFinalDocSent(?bool $finalDocSent): self
    {
        $this->finalDocSent = $finalDocSent;

        return $this;
    }

    /**
     * @param bool|null
     */
    public function getFinalDocSent(): ?bool
    {
        return $this->finalDocSent;
    }

    /**
     * @param bool|null $finalDocRequesterSent
     *
     * @return self
     */
    public function setFinalDocRequesterSent(?bool $finalDocRequesterSent): self
    {
        $this->finalDocRequesterSent = $finalDocRequesterSent;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFinalDocRequesterSent(): ?bool
    {
        return $this->finalDocRequesterSent;
    }

    /**
     * @param bool|null $finalDocObserverSent
     *
     * @return self
     */
    public function setFinalDocObserverSent(?bool $finalDocObserverSent): self
    {
        $this->finalDocObserverSent = $finalDocObserverSent;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getFinalDocObserverSent(): ?bool
    {
        return $this->finalDocObserverSent;
    }

    /**
     * @param string|null $descripton
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $certificateType
     *
     * @return self
     */
    public function setCertificateType(?string $certificateType): self
    {
        $this->certificateType = $certificateType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCertificateType(): ?string
    {
        return $this->certificateType;
    }

    /**
     * @param string|null $language
     *
     * @return self
     */
    public function setLanguage(?string $language): self
    {
        $this->language = $language;

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
     * @param int|null $handwrittenSignatureMode
     *
     * @return self
     */
    public function setHandwrittenSignatureMode(?int $handwrittenSignatureMode): self
    {
        $this->handwrittenSignatureMode = $handwrittenSignatureMode;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getHandwrittenSignatureMode(): ?int
    {
        return $this->handwrittenSignatureMode;
    }

    /**
     * @param string|null $chainingMode
     *
     * @return self
     */
    public function setChainingMode(?string $chainingMode): self
    {
        $this->chainingMode = $chainingMode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getChainingMode(): ?string
    {
        return $this->chainingMode;
    }

    /**
     * @param array|null $finalDocCCeMails
     *
     * @return self
     */
    public function setFinalDocCCeMails(?array $finalDocCCeMails): self
    {
        $this->finalDocCCeMails = $finalDocCCeMails;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFInalDocCCemails(): ?array
    {
        return $this->finalDocCCeMails;
    }

    /**
     * @param RedirectionConfig|null $autoValidationRedirection
     *
     * @return self
     */
    public function setAutoValifationRedirection(?RedirectionConfig $autoValidationRedirection):self
    {
        $this->autoValidationRedirection = $autoValidationRedirection;

        return $this;
    }

    /**
     * @return RedirectionConfig|null
     */
    public function getAutoValifationRedirection(): ?RedirectionConfig
    {
        return $this->autoValidationRedirection;
    }

    /**
     * @param string|null $redirectPolicy
     *
     * @return self
     */
    public function setRedirectPolicy(?string $redirectPolicy): self
    {
        $this->redirectPolicy = $redirectPolicy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectPolicy(): ?string
    {
        return $this->redirectPolicy;
    }

     /**
     * @param int|null $redirectWait
     *
     * @return self
     */
    public function setRedirectWait(?int $redirectWait): self
    {
        $this->redirectWait = $redirectWait;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRedirectWait(): ?int
    {
        return $this->redirectWait;
    }

    /**
     * @param bool|null $autoSendAgreements
     *
     * @return self
     */
    public function setAutoSendAgreements(?bool $autoSendAgreements): self
    {
        $this->autoSendAgreements = $autoSendAgreements;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getAutoSendAgreements(): ?bool
    {
        return $this->autoSendAgreements;
    }

    /**
     * @param string|null $operator
     *
     * @return self
     */
    public function setOperator(?string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOperator(): ?string
    {
        return $this->operator;
    }

    /**
     * @param string|null $registrationCallbackURL
     *
     * @return self
     */
    public function setRegistrationCallbackUrl(string $registrationCallbackURL): self
    {
        $this->registrationCallbackURL = $registrationCallbackURL;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationCallbackUrl(): ?string
    {
        return $this->registrationCallbackURL;
    }

    /**
     * @param RedirectionConfig|null $successRedirection
     *
     * @return self
     */
    public function setSuccessRedirection(?RedirectionConfig $successRedirection): self
    {
        $this->successRedirection = $successRedirection;

        return $this;
    }

    /**
     * @return RedirectionConfig|null
     */
    public function getSuccessRedirection(): ?RedirectionConfig
    {
        return $this->successRedirection;
    }

    /**
     * @param RedirectionConfig|null $cancelRedirection
     *
     * @return self
     */
    public function setCancelRedirection(?RedirectionConfig $cancelRedirection): self
    {
        $this->cancelRedirection = $cancelRedirection;

        return $this;
    }

    /**
     * @return RedirectionConfig|null
     */
    public function getCancelRedirection():? RedirectionConfig
    {
        return $this->cancelRedirection;
    }

    /**
     * @param RedirectionConfig|null $failRedirection
     *
     * @return self
     */
    public function setFailRedirection(?RedirectionConfig $failRedirection): self
    {
        $this->failRedirection = $failRedirection;

        return $this;
    }

    /**
     * @return RedirectionConfig|null
     */
    public function getFailRedirection(): ?RedirectionConfig
    {
        return $this->failRedirection;
    }

    /**
     * @param string|null $invitationMessage
     *
     * @return self
     */
    public function setInvitationMessage(?string $invitationMessage): self
    {
        $this->invitationMessage = $invitationMessage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInvitationMessage(): ?string
    {
        return $this->invitationMessage;
    }

}
