<?php

namespace Mpp\UniversignBundle\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
class Signer
{
    const ROLE_SIGNER = 'signer';
    const ROLE_OBSERVER = 'observer';
    const REDIRECT_POLICY_DASHBOARD = 'dashboard';
    const REDIRECT_POLICY_QUICK = 'quick';

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;

    /**
     * @var string
     */
    protected $organization;

    /**
     * @var string
    */
    protected $profile;

    /**
     * @var string
     */
    protected $emailAddress;

    /**
     * @var string
     */
    protected $phoneNum;

    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $role;

    /**
     * @var \Laminas\XmlRpc\Value\DateTime
     */
    protected $birthDate;

    /**
     * @var string
     */
    protected $universignId;

    /**
     * @var array
     */
    protected $successRedirection;

    /**
     * @var array
     */
    protected $cancelRedirection;

    /**
     * @var array
     */
    protected $failRedirection;

    /**
     * @var string
     */
    protected $certificateType;

    /**
     * @var array
     */
    protected $idDocuments;

    /**
     * @var string
     */
    protected $validationSessionId;

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
    protected $invitationMessage;


    public function __construct()
    {
        $this->successRedirection = [];
        $this->cancelRedirection = [];
        $this->failRedirection = [];
        $this->idDocuments = [];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public static function configureData(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('firstname', null)->setAllowedTypes('firstname', ['string', 'null'])
            ->setDefault('lastname', null)->setAllowedTypes('lastname', ['string', 'null'])
            ->setDefault('organization', null)->setAllowedTypes('organization', ['string', 'null'])
            ->setDefault('emailAddress', null)->setAllowedTypes('emailAddress', ['string', 'null'])
            ->setDefault('phoneNum', null)->setAllowedTypes('phoneNum', ['string', 'null'])
            ->setDefault('profile', null)->setAllowedTypes('profile', ['string', 'null'])
            ->setDefault('language', null)->setAllowedTypes('language', ['string', 'null'])
            ->setDefault('role', 'signer')->setAllowedValues('role', ['signer', 'observer'])
            ->setDefault('birthDate', null)->setAllowedTypes('birthDate', ['DateTime', 'null'])->setNormalizer('birthDate', function(Options $options, $value): ?\Laminas\XmlRpc\Value\DateTime {
                if (null === $value) {
                    return null;
                }

                return new \Laminas\XmlRpc\Value\DateTime($value);
            })
            ->setDefault('universignId', null)->setAllowedTypes('universignId', ['string', 'null'])
            ->setDefault('successRedirection', null)->setAllowedTypes('successRedirection', ['array', 'null'])
            ->setDefault('cancelRedirection', null)->setAllowedTypes('cancelRedirection', ['array', 'null'])
            ->setDefault('failRedirection', null)->setAllowedTypes('failRedirection', ['array', 'null'])
            ->setDefault('validationSessionId', null)->setAllowedTypes('validationSessionId', ['string', 'null'])
            ->setDefault('idDocuments', null)->setAllowedTypes('idDocuments', ['array', 'null'])
            ->setDefault('certificateType', null)->setAllowedTypes('certificateType', ['string', 'null'])
            ->setDefault('redirectPolicy', 'dashboard')->setAllowedValues('redirectPolicy', ['dashboard', 'quick'])
            ->setDefault('redirectWait', 5)->setAllowedTypes('redirectWait', ['int'])
            ->setDefault('autoSendAgreements', null)->setAllowedTypes('autoSendAgreements', ['bool', 'null'])
            ->setDefault('invitationMessage', null)->setAllowedTypes('invitationMessage', ['string', 'null'])
        ;
    }

    /**
     * @param array $data
     *
     * @return self
     *
     * @throws UndefinedOptionsException If an option name is undefined
     * @throws InvalidOptionsException   If an option doesn't fulfill the
     *                                   specified validation rules
     * @throws MissingOptionsException   If a required option is missing
     * @throws OptionDefinitionException If there is a cyclic dependency between
     *                                   lazy options and/or normalizers
     * @throws NoSuchOptionException     If a lazy option reads an unavailable option
     * @throws AccessException           If called from a lazy option or normalizer
     */
    public static function createFromArray(array $data): self
    {
        $resolver = new OptionsResolver();
        self::configureData($resolver);
        $resolvedData = $resolver->resolve($data);

        return (new Signer())
            ->setFirstname($resolvedData['firstname'])
            ->setLastname($resolvedData['lastname'])
            ->setOrganization($resolvedData['organization'])
            ->setProfile($resolvedData['profile'])
            ->setEmailAddress($resolvedData['emailAddress'])
            ->setPhoneNum($resolvedData['phoneNum'])
            ->setLanguage($resolvedData['language'])
            ->setRole($resolvedData['role'])
            ->setBirthDate($resolvedData['birthDate'])
            ->setUniversignId($resolvedData['universignId'])
            ->setSuccessRedirection($resolvedData['successRedirection'])
            ->setCancelRedirection($resolvedData['cancelRedirection'])
            ->setFailRedirection($resolvedData['failRedirection'])
            ->setCertificateType($resolvedData['certificateType'])
            ->setIdDocuments($resolvedData['idDocuments'])
            ->setValidationSessionId($resolvedData['validationSessionId'])
            ->setRedirectPolicy($resolvedData['redirectPolicy'])
            ->setRedirectWait($resolvedData['redirectWait'])
            ->setAutoSendAgreements($resolvedData['autoSendAgreements'])
            ->setInvitationMessage($resolvedData['invitationMessage'])
        ;
    }


    /**
     * @param string|null $firstname
     *
     * @return self
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $lastname
     *
     * @return self
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $organization
     *
     * @return self
     */
    public function setOrganization(?string $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrganization(): ?string
    {
        return $this->organization;
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
     * @param string|null $emailAddress
     *
     * @return self
     */
    public function setEmailAddress(?string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    /**
     * @param string|null $phoneNum
     *
     * @return self
     */
    public function setPhoneNum(?string $phoneNum): self
    {
        $this->phoneNum = $phoneNum;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNum(): ?string
    {
        return $this->phoneNum;
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
     * @param string|null $role
     *
     * @return self
     */
    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param \Laminas\XmlRpc\Value\DateTime|null $birthDate
     *
     * @return self
     */
    public function setBirthDate(?\Laminas\XmlRpc\Value\DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBirthDate(): ?\Laminas\XmlRpc\Value\DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param string|null $universignId
     *
     * @return self
     */
    public function setUniversignId(?string $universignId): self
    {
        $this->universignId = $universignId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUniversignId(): ?string
    {
        return $this->universignId;
    }

    /**
     * @param array|null $successRedirection
     *
     * @return self
     */
    public function setSuccessRedirection(?array $successRedirection): self
    {
        $this->successRedirection = $successRedirection;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getSuccessRedirection(): ?array
    {
        return $this->successRedirection;
    }

    /**
     * @param array|null $failRedirection
     *
     * @return self
     */
    public function setFailRedirection(?array $failRedirection): self
    {
        $this->failRedirection = $failRedirection;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFailRedirection(): ?array
    {
        return $this->failRedirection;
    }

    /**
     * @param array|null $cancelRedirection
     *
     * @return self
     */
    public function setCancelRedirection(?array $cancelRedirection): self
    {
        $this->cancelRedirection = $cancelRedirection;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCancelRedirection(): ?array
    {
        return $this->cancelRedirection;
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
     * @param array|null $idDocument
     *
     * @return self
     */
    public function setIdDocuments(?array $idDocument): self
    {
        if (null !== $idDocument) {
            $this->idDocument = RegistrationRequest::createFromArray($idDocument);
        }

        return $this;
    }

    /**
     * @return array|null
     */
    public function getIdDocument(): ?array
    {
        return $this->idDocument;
    }

    /**
     * @param string|null $validationSessionId
     *
     * @return self
     */
    public function setValidationSessionId(?string $validationSessionId): self
    {
        $this->validationSessionId = $validationSessionId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValidationSessionId(): ?string
    {
        return $this->validationSessionId;
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
