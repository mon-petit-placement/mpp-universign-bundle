<?php

namespace Mpp\UniversignBundle\Requester;

use Laminas\XmlRpc\Client;
use Laminas\XmlRpc\Client\Exception\FaultException;
use Laminas\XmlRpc\Value\Base64;
use Laminas\XmlRpc\Value\DateTime;
use Mpp\UniversignBundle\Model\InitiatorInfo;
use Mpp\UniversignBundle\Model\MatchingFilter;
use Mpp\UniversignBundle\Model\MatchingResult;
use Mpp\UniversignBundle\Model\RedirectionConfig;
use Mpp\UniversignBundle\Model\SignerInfo;
use Mpp\UniversignBundle\Model\SignOptions;
use Mpp\UniversignBundle\Model\TransactionInfo;
use Mpp\UniversignBundle\Model\TransactionRequest;
use Mpp\UniversignBundle\Model\TransactionResponse;
use Mpp\UniversignBundle\Model\ValidationRequest;
use Mpp\UniversignBundle\Model\ValidatorResult;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationAuthority extends XmlRpcRequester implements RegistrationAuthorityInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $entrypoint;

    /**
     * @var Router
     */
    protected $router;

    public function __construct(LoggerInterface $logger, Router $router, array $entrypoint, array $options)
    {
        $this->logger = $logger;
        $this->router = $router;
        $this->entrypoint = $entrypoint;
        $this->options = $options;

        parent::__construct();
    }

    /**
     * @return string;
     */
    public function getUrl()
    {
        return $this->entrypoint['ra'];
    }

    private function call(string $method, $args)
    {
        $response = null;

        try {
            $response = $this->xmlRpcClient->call($method, self::flatten($args));
            $this->logger->info(sprintf('[Universign - %s] SUCCESS', $method));
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - %s] ERROR (%s): %s', $method, $fe->getCode(), $fe->getMessage()));
        }

        return $response;
    }

    public function checkOperatorStatus(string $email): int
    {
        return $this->call('ra.checkOperatorStatus', $email);
    }

    public function matchAccount(MatchingFilter $matchingFilter): array
    {
        return $this->call('ra.matchAccount', $matchingFilter);
    }

    public function getCertificateAgreement(string $email)
    {
        return $this->call('ra.getCertificateAgreement', $email);
    }

    public function revokeCertificate(string $emailOrPhoneNumber)
    {
        return $this->call('ra.revokeCertificate', $emailOrPhoneNumber);
    }

    public function revokeMyCertificate(string $emailOrPhoneNumber)
    {
        return $this->call('ra.revokeMyCertificate', $emailOrPhoneNumber);
    }

    public function validate(ValidationRequest $validationRequest): ValidatorResult
    {
        if (null === $validationRequest->getCallbackURL()) {
            $validationRequest->setCallbackURL(
                $this->router->generate(
                    'mpp_universign_callback',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            );
        }

        return ValidatorResult::createFromArray($this->call('validator.validate', [$validationRequest]));
    }

    public function getResult(string $validationSessionId): ValidatorResult
    {
        return ValidatorResult::createFromArray($this->call('validator.getResult', $validationSessionId));
    }
}
