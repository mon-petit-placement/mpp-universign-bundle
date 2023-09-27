<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Model\MatchingFilter;
use Mpp\UniversignBundle\Model\MatchingResult;
use Mpp\UniversignBundle\Model\ValidationRequest;
use Mpp\UniversignBundle\Model\ValidatorResult;

interface RegistrationAuthorityInterface
{
    public const OPERATOR_STATUS_NOT_OPERATOR = 0;
    public const OPERATOR_STATUS_RA_OPERATOR = 1;
    public const OPERATOR_STATUS_INVITED_RA_OPERATOR = 2;
    public const OPERATOR_STATUS_NOT_EXISTENT = 5;

    /**
     * @param string $email Email of User
     * @return int|null Returns the operator status. The return value can be:
     * 0 The user is not an operator.
     * 1 The user is an RA operator.
     * 2 The user is invited to be an RA operator.
     * 5 The User is not existent.
     */
    public function checkOperatorStatus(string $email): ?int;

    /**
     * @param MatchingFilter $matchingFilter
     *
     * @return MatchingResult[] Returns users matching the provided filter.
     */
    public function matchAccount(MatchingFilter $matchingFilter): array;

    /**
     * This service allows the admin of an organization to retrieve the signed
     * certificate application agreement of a user (belonging to his organization),
     * identified by his email.
     * This service is limited to organizations having this feature explicitly granted.
     *
     * @param string $email Email of User
     *
     * @return byte[]|null Certificate
     */
    public function getCertificateAgreement(string $email);

    /**
     * This service allows the admin of an organization to revoke a user’s certifi-
     * cate identified by its emails or phone number.
     *
     * @param string $emailOrPhoneNumber Email or Phone number of User
     */
    public function revokeCertificate(string $emailOrPhoneNumber);

    /**
     * This service allows a user to revoke his own certificate.
     *
     * @param string $emailOrPhoneNumber
     */
    public function revokeMyCertificate(string $emailOrPhoneNumber);

    /**
     * Sends a validation request in order to validate ID documents with the provided user info
     * and getting a validation result. If the manual validation is
     * activated in the request and there is a timeout when attempting the auto-matic validation,
     * then a validation result will be returned with the status pending.
     * If the manual validation is not activated then an invalid status will be returned.
     *
     * Optionnaly, a callback URL can be provided. This URL will be requested
     * when the validation session is completed (i.e. it ended with a final status).
     *
     * @param ValidationRequest $validationRequest
     *
     * @return ValidatorResult|null
     */
    public function validate(ValidationRequest $validationRequest): ?ValidatorResult;

    /**
     * Retrieves the validation result of the validation session that matches the
     * given id.
     *
     * @param string $validationSessionId
     *
     * @return ValidatorResult|null
     */
    public function getResult(string $validationSessionId): ?ValidatorResult;
}
