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

class Signer
{
    public const ROLE_SIGNER = 'signer';
    public const ROLE_OBSERVER = 'observer';

    protected ?string $firstname;

    protected ?string $lastname;

    protected ?string $organization;

    protected ?string $profile;

    protected ?string $emailAddress;

    protected ?string $phoneNum;

    protected string $language;

    protected string $role;

    protected ?\DateTimeInterface $birthDate;

    protected ?string $universignId;

    protected array $successRedirection;

    protected array $cancelRedirection;

    protected array $failRedirection;

    protected ?string $certificateType;

    protected ?RegistrationRequest $idDocuments;

    protected ?string $validationSessionId;

    protected ?string $redirectPolicy;

    protected int $redirectWait;

    protected ?bool $autoSendAgreements;

    protected ?string $invitationMessage;

    public static function configureData(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('firstname', null)->setAllowedTypes('firstname', ['null', 'string'])
            ->setDefault('lastname', null)->setAllowedTypes('lastname', ['null', 'string'])
            ->setDefault('organization', null)->setAllowedTypes('organization', ['null', 'string'])
            ->setDefault('emailAddress', null)->setAllowedTypes('emailAddress', ['null', 'string'])
            ->setDefault('phoneNum', null)->setAllowedTypes('phoneNum', ['null', 'string'])
            ->setDefault('profile', null)->setAllowedTypes('profile', ['null', 'string'])
            ->setDefault('language', 'en')->setAllowedValues('language', [Language::BULGARIAN, Language::CATALAN, Language::GERMAN, Language::ENGLISH, Language::SPANISH, Language::FRENCH, Language::ITALIAN, Language::DUTCH, Language::POLISH, Language::PORTUGUESE, Language::ROMANIAN])
            ->setDefault('role', self::ROLE_SIGNER)->setAllowedValues('role', [self::ROLE_SIGNER, self::ROLE_OBSERVER])
            ->setDefault('birthDate', null)->setAllowedTypes('birthDate', [\DateTimeInterface::class, 'null'])
            ->setDefault('universignId', null)->setAllowedTypes('universignId', ['null', 'string'])
            ->setDefault('successRedirection', [])->setAllowedTypes('successRedirection', ['array'])
            ->setDefault('cancelRedirection', [])->setAllowedTypes('cancelRedirection', ['array'])
            ->setDefault('failRedirection', [])->setAllowedTypes('failRedirection', ['array'])
            ->setDefault('certificateType', null)->setAllowedValues('certificateType', [null, CertificateType::SIMPLE, CertificateType::CERTIFIED, CertificateType::ADVANCED])
            ->setDefault('idDocuments', null)->setAllowedTypes('idDocuments', ['array', RegistrationRequest::class, 'null'])->setNormalizer('idDocuments', function (Options $options, $value) {
                if ($value instanceof RegistrationRequest || null === $value) {
                    return $value;
                }

                return RegistrationRequest::createFromArray($value);
            })
            ->setDefault('validationSessionId', null)->setAllowedTypes('validationSessionId', ['null', 'string'])
            ->setDefault('redirectPolicy', null)->setAllowedValues('redirectPolicy', ['null', 'dashboard', 'quick'])
            ->setDefault('redirectWait', 5)->setAllowedTypes('redirectWait', ['int'])->setNormalizer('redirectWait', function (Options $options, $value) {
                if ('quick' === $options['redirectPolicy']) {
                    return null;
                }

                return $value;
            })
            ->setDefault('autoSendAgreements', null)->setAllowedTypes('autoSendAgreements', ['null', 'bool'])
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
            ->setFirstname($resolvedOptions['firstname'])
            ->setLastname($resolvedOptions['lastname'])
            ->setOrganization($resolvedOptions['organization'])
            ->setProfile($resolvedOptions['profile'])
            ->setEmailAddress($resolvedOptions['emailAddress'])
            ->setPhoneNum($resolvedOptions['phoneNum'])
            ->setLanguage($resolvedOptions['language'])
            ->setRole($resolvedOptions['role'])
            ->setBirthDate($resolvedOptions['birthDate'])
            ->setUniversignId($resolvedOptions['universignId'])
            ->setSuccessRedirection($resolvedOptions['successRedirection'])
            ->setCancelRedirection($resolvedOptions['cancelRedirection'])
            ->setFailRedirection($resolvedOptions['failRedirection'])
            ->setCertificateType($resolvedOptions['certificateType'])
            ->setIdDocuments($resolvedOptions['idDocuments'])
            ->setValidationSessionId($resolvedOptions['validationSessionId'])
            ->setRedirectPolicy($resolvedOptions['redirectPolicy'])
            ->setRedirectWait($resolvedOptions['redirectWait'])
            ->setAutoSendAgreements($resolvedOptions['autoSendAgreements'])
            ->setInvitationMessage($resolvedOptions['invitationMessage'])
        ;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setOrganization(?string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
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

    public function setEmailAddress(?string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setPhoneNum(?string $phoneNum): self
    {
        $this->phoneNum = $phoneNum;

        return $this;
    }

    public function getPhoneNum(): ?string
    {
        return $this->phoneNum;
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

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setUniversignId(?string $universignId): self
    {
        $this->universignId = $universignId;

        return $this;
    }

    public function getUniversignId(): ?string
    {
        return $this->universignId;
    }

    public function setSuccessRedirection(array $successRedirection): self
    {
        $this->successRedirection = $successRedirection;

        return $this;
    }

    public function getSuccessRedirection(): array
    {
        return $this->successRedirection;
    }

    public function setFailRedirection(array $failRedirection): self
    {
        $this->failRedirection = $failRedirection;

        return $this;
    }

    public function getFailRedirection(): array
    {
        return $this->failRedirection;
    }

    public function setCancelRedirection(array $cancelRedirection): self
    {
        $this->cancelRedirection = $cancelRedirection;

        return $this;
    }

    public function getCancelRedirection(): array
    {
        return $this->cancelRedirection;
    }

    public function setCertificateType(?string $certificateType): self
    {
        $this->certificateType = $certificateType;

        return $this;
    }

    public function getCertificateType(): ?string
    {
        return $this->certificateType;
    }

    public function setIdDocuments(?RegistrationRequest $idDocuments): self
    {
        $this->idDocuments = $idDocuments;

        return $this;
    }

    public function getIdDocuments(): ?RegistrationRequest
    {
        return $this->idDocuments;
    }

    public function setValidationSessionId(?string $validationSessionId): self
    {
        $this->validationSessionId = $validationSessionId;

        return $this;
    }

    public function getValidationSessionId(): ?string
    {
        return $this->validationSessionId;
    }

    public function setRedirectPolicy(?string $redirectPolicy): self
    {
        $this->redirectPolicy = $redirectPolicy;

        return $this;
    }

    public function getRedirectPolicy(): ?string
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
