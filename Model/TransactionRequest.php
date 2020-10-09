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
     * @var array<Signer>
     */
    protected $signers;

    /**
     * @var array<Document>
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
    protected $successRedirection;

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
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('profile', null)->setAllowedTypes('profile', ['null', 'string'])
            ->setDefault('customId', null)->setAllowedTypes('customId', ['null', 'string'])
            ->setDefault('signers', [])->setAllowedTypes('signers', ['array'])->setNormalizer('signers', function (Options $options, $values): array {
                $signers = [];
                foreach ($values as $value) {
                    $signers[] = Signer::createFromArray($value);
                }

                return $signers;
            })
            ->setDefault('documents', [])->setAllowedTypes('documents', ['array'])->setNormalizer('documents', function (Options $options, $values): array {
                $documents = [];
                foreach ($values as $name => $value) {
                    $documents[$name] = Document::createFromArray($value);
                }

                return $documents;
            })
            ->setDefault('mustContactFirstSigner', false)->setAllowedTypes('mustContactFirstSigner', ['bool'])
            ->setDefault('finalDocSent', false)->setAllowedTypes('finalDocSent', ['bool'])
            ->setDefault('finalDocRequesterSent', false)->setAllowedTypes('finalDocRequesterSent', ['bool'])
            ->setDefault('finalDocObserverSent', false)->setAllowedTypes('finalDocObserverSent', ['bool'])
            ->setDefault('description', null)->setAllowedTypes('description', ['null', 'string'])
            ->setDefault('certificateType', CertificateType::SIMPLE)->setAllowedValues('certificateType', [null, CertificateType::SIMPLE, CertificateType::CERTIFIED, CertificateType::ADVANCED])
            ->setDefault('language', 'en')->setAllowedValues('language', [Language::BULGARIAN, Language::CATALAN, Language::GERMAN, Language::ENGLISH, Language::SPANISH, Language::FRENCH, Language::ITALIAN, Language::DUTCH, Language::POLISH, Language::PORTUGUESE, Language::ROMANIAN])
            ->setDefault('handwrittenSignatureMode', null)->setAllowedValues('handwrittenSignatureMode', [null, "0", "1", "2"])
            ->setDefault('chainingMode', 'email')->setAllowedValues('chainingMode', ['none', 'email', 'web'])
            ->setDefault('finalDocCCeMails', null)->setAllowedTypes('finalDocCCeMails', ['null', 'string'])
            ->setDefault('autoValidationRedirection', null)->setAllowedTypes('autoValidationRedirection', ['null', RedirectionConfig::class])
            ->setDefault('redirectPolicy', 'dashboard')->setAllowedValues('redirectPolicy', ['dashboard', 'quick'])
            ->setDefault('redirectWait', 5)->setAllowedTypes('redirectWait', ['int'])->setNormalizer('redirectWait', function (Options $options, $value) {
                if ('quick' === $options['redirectPolicy']) {
                    return null;
                }

                return $value;
            })
            ->setDefault('autoSendAgreements', null)->setAllowedTypes('autoSendAgreements', ['null', 'bool'])
            ->setDefault('operator', null)->setAllowedTypes('operator', ['null', 'string'])->setNormalizer('operator', function (Options $options, $value) {
                if (CertificateType::ADVANCED !== $options['certificateType']) {
                    return null;
                }

                return $value;
            })
            ->setDefault('registrationCallbackURL', null)->setAllowedTypes('registrationCallbackURL', ['null', 'string'])->setNormalizer('registrationCallbackURL', function (Options $options, $value) {
                if (CertificateType::ADVANCED !== $options['certificateType']) {
                    return null;
                }

                return $value;
            })
            ->setDefault('successRedirection', null)->setAllowedTypes('successRedirection', ['null', RedirectionConfig::class])
            ->setDefault('cancelRedirection', null)->setAllowedTypes('cancelRedirection', ['null', RedirectionConfig::class])
            ->setDefault('failRedirection', null)->setAllowedTypes('failRedirection', ['null', RedirectionConfig::class])
            ->setDefault('invitationMessage', null)->setAllowedTypes('invitationMessage', ['null', 'string'])
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
    public static function createFromArray(array $options): self
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedOptions = $resolver->resolve($options);

        return (new self())
            ->setProfile($resolvedOptions['profile'])
            ->setCustomId($resolvedOptions['customId'])
            ->setSigners($resolvedOptions['signers'])
            ->setDocuments($resolvedOptions['documents'])
            ->setMustContactFirstSigner($resolvedOptions['mustContactFirstSigner'])
            ->setFinalDocSent($resolvedOptions['finalDocSent'])
            ->setFinalDocRequesterSent($resolvedOptions['finalDocRequesterSent'])
            ->setFinalDocObserverSent($resolvedOptions['finalDocObserverSent'])
            ->setDescription($resolvedOptions['description'])
            ->setCertificateType($resolvedOptions['certificateType'])
            ->setLanguage($resolvedOptions['language'])
            ->setHandwrittenSignatureMode($resolvedOptions['handwrittenSignatureMode'])
            ->setChainingMode($resolvedOptions['chainingMode'])
            ->setFinalDocCCeMails($resolvedOptions['finalDocCCeMails'])
            ->setAutoValidationRedirection($resolvedOptions['autoValidationRedirection'])
            ->setRedirectPolicy($resolvedOptions['redirectPolicy'])
            ->setRedirectWait($resolvedOptions['redirectWait'])
            ->setAutoSendAgreements($resolvedOptions['autoSendAgreements'])
            ->setOperator($resolvedOptions['operator'])
            ->setRegistrationCallbackURL($resolvedOptions['registrationCallbackURL'])
            ->setSuccessRedirection($resolvedOptions['successRedirection'])
            ->setCancelRedirection($resolvedOptions['cancelRedirection'])
            ->setFailRedirection($resolvedOptions['failRedirection'])
            ->setInvitationMessage($resolvedOptions['invitationMessage'])
        ;
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
     * @param Signer $signer
     *
     * @return self
     */
    public function addSigner(Signer $signer): self
    {
        $this->signers[] = $signer;

        return $this;
    }

    /**
     * @param array<Signer> $signers
     *
     * @return self
     */
    public function setSigners(array $signers): self
    {
        $this->signers = $signers;

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
     * @param string   $name
     * @param Document $document
     *
     * @return self
     */
    public function addDocument(string $name, Document $document): self
    {
        $this->documents[$name] = $document;

        return $this;
    }

    /**
     * @param array<Document> $documents
     *
     * @return self
     */
    public function setDocuments(array $documents): self
    {
        $this->documents = $documents;

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
    public function getFinalDocCCemails(): ?array
    {
        return $this->finalDocCCeMails;
    }

    /**
     * @param RedirectionConfig|null $autoValidationRedirection
     *
     * @return self
     */
    public function setAutoValidationRedirection(?RedirectionConfig $autoValidationRedirection): self
    {
        $this->autoValidationRedirection = $autoValidationRedirection;

        return $this;
    }

    /**
     * @return RedirectionConfig|null
     */
    public function getAutoValidationRedirection(): ?RedirectionConfig
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
    public function setRegistrationCallbackURL(?string $registrationCallbackURL): self
    {
        $this->registrationCallbackURL = $registrationCallbackURL;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationCallbackURL(): ?string
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
    public function getCancelRedirection(): ? RedirectionConfig
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
