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

    public function __construct(LoggerInterface $logger, Router $router, array $entrypoint, array $clientOptions)
    {
        $this->logger = $logger;
        $this->router = $router;
        $this->entrypoint = $entrypoint;
        parent::__construct($clientOptions);
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

    public function checkOperatorStatus(string $email): ?int
    {
        return $this->call('ra.checkOperatorStatus', $email);
    }

    public function matchAccount(MatchingFilter $matchingFilter): array
    {
        $results = [];
        $dataResult = $this->call('matcher.matchAccount', [$matchingFilter]);

        if (is_array($dataResult)) {
            foreach ($dataResult as $item) {
                $results[] = MatchingResult::createFromArray($item);
            }
        }

        return $results;
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

            $this->logger->info(sprintf('[Universign - validate] define default callback URL : "%s"', $validationRequest->getCallbackURL()));
        }

        $result = $this->call('validator.validate', [$validationRequest]);

        if (null === $result) {
            return null;
        }

        return ValidatorResult::createFromArray($result);
    }

    public function getResult(string $validationSessionId): ?ValidatorResult
    {
        $result = $this->call('validator.getResult', $validationSessionId);

        if (null === $result) {
            return null;
        }

        return ValidatorResult::createFromArray($result);
    }
}
