<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Exception\FaultException;
use Mpp\UniversignBundle\Model\MatchingFilter;
use Mpp\UniversignBundle\Model\MatchingResult;
use Mpp\UniversignBundle\Model\ValidationRequest;
use Mpp\UniversignBundle\Model\ValidatorResult;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationAuthority extends XmlRpcRequester implements RegistrationAuthorityInterface
{
    protected LoggerInterface $logger;

    protected array $entrypoint;

    protected Router $router;

    public function __construct(LoggerInterface $logger, Router $router, array $entrypoint, array $clientOptions)
    {
        $this->logger = $logger;
        $this->router = $router;
        $this->entrypoint = $entrypoint;

        parent::__construct($clientOptions);
    }

    public function getUrl(): string
    {
        return $this->entrypoint['ra'];
    }


    /**
     * @throws FaultException
     */
    public function checkOperatorStatus(string $email): ?int
    {
        return $this->call('ra.checkOperatorStatus', $email)->value();
    }

    /**
     * @throws FaultException
     */
    public function matchAccount(MatchingFilter $matchingFilter): array
    {
        $results = [];
        $dataResult = $this->call('matcher.matchAccount', [$matchingFilter])->value();

        if (is_array($dataResult)) {
            foreach ($dataResult as $item) {
                $results[] = MatchingResult::createFromArray($item);
            }
        }

        return $results;
    }

    /**
     * @throws FaultException
     */
    public function getCertificateAgreement(string $email)
    {
        return $this->call('ra.getCertificateAgreement', $email);
    }

    /**
     * @throws FaultException
     */
    public function revokeCertificate(string $emailOrPhoneNumber)
    {
        return $this->call('ra.revokeCertificate', $emailOrPhoneNumber);
    }

    /**
     * @throws FaultException
     */
    public function revokeMyCertificate(string $emailOrPhoneNumber)
    {
        return $this->call('ra.revokeMyCertificate', $emailOrPhoneNumber);
    }

    /**
     * @throws FaultException
     */
    public function validate(ValidationRequest $validationRequest): ?ValidatorResult
    {
        if (null === $validationRequest->getCallbackURL()) {
            $validationRequest->setCallbackURL(
                $this->router->generate(
                    'mpp_universign_callback',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            );

            $this->logger->debug(sprintf('[Universign - validate] define default callback URL : "%s"', $validationRequest->getCallbackURL()));
        }

        $result = $this->call('validator.validate', [$validationRequest]);

        if (null === $result) {
            return null;
        }

        return ValidatorResult::createFromArray($result);
    }

    /**
     * @throws FaultException
     */
    public function getResult(string $validationSessionId): ?ValidatorResult
    {
        $result = $this->call('validator.getResult', $validationSessionId);

        if (null === $result) {
            return null;
        }

        return ValidatorResult::createFromArray($result);
    }
}
