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
    protected ?string $profile;

    protected ?string $customId;

    /**
     * @var array<Signer>
     */
    protected array $signers;

    /**
     * @var array<Document>
     */
    protected array $documents;

    protected bool $mustContactFirstSigner;

    protected bool $finalDocSent;

    protected bool $finalDocRequesterSent;

    protected bool $finalDocObserverSent;

    protected ?string $description;

    protected string $certificateType;

    protected string $language;

    protected ?int $handwrittenSignatureMode;

    protected string $chainingMode;

    protected ?array $finalDocCCeMails;

    protected ?RedirectionConfig $autoValidationRedirection;

    protected string $redirectPolicy;

    protected int $redirectWait;

    protected ?bool $autoSendAgreements;

    protected ?string $operator;

    protected ?string $registrationCallbackURL;

    protected ?RedirectionConfig $successRedirection;

    protected ?RedirectionConfig $failRedirection;

    protected ?RedirectionConfig $cancelRedirection;

    protected ?string $invitationMessage;

    public static function configureData(OptionsResolver $resolver): void
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

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setCustomId(?string $customId): self
    {
        $this->customId = $customId;

        return $this;
    }

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function addSigner(Signer $signer): self
    {
        $this->signers[] = $signer;

        return $this;
    }

    /**
     * @param array<Signer> $signers
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

    public function addDocument(string $name, Document $document): self
    {
        $this->documents[$name] = $document;

        return $this;
    }

    /**
     * @param array<Document> $documents
     */
    public function setDocuments(array $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

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

    public function setMustContactFirstSigner(bool $mustContactFirstSigner): self
    {
        $this->mustContactFirstSigner = $mustContactFirstSigner;

        return $this;
    }

    public function getMustContactFirstSigner(): bool
    {
        return $this->mustContactFirstSigner;
    }

    public function setFinalDocSent(bool $finalDocSent): self
    {
        $this->finalDocSent = $finalDocSent;

        return $this;
    }

    public function getFinalDocSent(): bool
    {
        return $this->finalDocSent;
    }

    public function setFinalDocRequesterSent(bool $finalDocRequesterSent): self
    {
        $this->finalDocRequesterSent = $finalDocRequesterSent;

        return $this;
    }

    public function getFinalDocRequesterSent(): bool
    {
        return $this->finalDocRequesterSent;
    }

    public function setFinalDocObserverSent(bool $finalDocObserverSent): self
    {
        $this->finalDocObserverSent = $finalDocObserverSent;

        return $this;
    }

    public function getFinalDocObserverSent(): bool
    {
        return $this->finalDocObserverSent;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setCertificateType(string $certificateType): self
    {
        $this->certificateType = $certificateType;

        return $this;
    }

    public function getCertificateType(): string
    {
        return $this->certificateType;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setHandwrittenSignatureMode(?int $handwrittenSignatureMode): self
    {
        $this->handwrittenSignatureMode = $handwrittenSignatureMode;

        return $this;
    }

    public function getHandwrittenSignatureMode(): ?int
    {
        return $this->handwrittenSignatureMode;
    }

    public function setChainingMode(string $chainingMode): self
    {
        $this->chainingMode = $chainingMode;

        return $this;
    }

    public function getChainingMode(): string
    {
        return $this->chainingMode;
    }

    public function setFinalDocCCeMails(?array $finalDocCCeMails): self
    {
        $this->finalDocCCeMails = $finalDocCCeMails;

        return $this;
    }

    public function getFinalDocCCemails(): ?array
    {
        return $this->finalDocCCeMails;
    }

    public function setAutoValidationRedirection(?RedirectionConfig $autoValidationRedirection): self
    {
        $this->autoValidationRedirection = $autoValidationRedirection;

        return $this;
    }

    public function getAutoValidationRedirection(): ?RedirectionConfig
    {
        return $this->autoValidationRedirection;
    }

    public function setRedirectPolicy(string $redirectPolicy): self
    {
        $this->redirectPolicy = $redirectPolicy;

        return $this;
    }

    public function getRedirectPolicy(): string
    {
        return $this->redirectPolicy;
    }

    public function setRedirectWait(int $redirectWait): self
    {
        $this->redirectWait = $redirectWait;

        return $this;
    }

    public function getRedirectWait(): int
    {
        return $this->redirectWait;
    }

    public function setAutoSendAgreements(?bool $autoSendAgreements): self
    {
        $this->autoSendAgreements = $autoSendAgreements;

        return $this;
    }

    public function getAutoSendAgreements(): ?bool
    {
        return $this->autoSendAgreements;
    }

    public function setOperator(?string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function setRegistrationCallbackURL(?string $registrationCallbackURL): self
    {
        $this->registrationCallbackURL = $registrationCallbackURL;

        return $this;
    }

    public function getRegistrationCallbackURL(): ?string
    {
        return $this->registrationCallbackURL;
    }

    public function setSuccessRedirection(?RedirectionConfig $successRedirection): self
    {
        $this->successRedirection = $successRedirection;

        return $this;
    }

    public function getSuccessRedirection(): ?RedirectionConfig
    {
        return $this->successRedirection;
    }

    public function setCancelRedirection(?RedirectionConfig $cancelRedirection): self
    {
        $this->cancelRedirection = $cancelRedirection;

        return $this;
    }

    public function getCancelRedirection(): ?RedirectionConfig
    {
        return $this->cancelRedirection;
    }

    public function setFailRedirection(?RedirectionConfig $failRedirection): self
    {
        $this->failRedirection = $failRedirection;

        return $this;
    }

    public function getFailRedirection(): ?RedirectionConfig
    {
        return $this->failRedirection;
    }

    public function setInvitationMessage(?string $invitationMessage): self
    {
        $this->invitationMessage = $invitationMessage;

        return $this;
    }

    public function getInvitationMessage(): ?string
    {
        return $this->invitationMessage;
    }
}
