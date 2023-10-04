<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Model\MatchingFilter;
use Mpp\UniversignBundle\Model\MatchingResult;
use Mpp\UniversignBundle\Model\ValidationRequest;
use Mpp\UniversignBundle\Model\ValidatorResult;
use PhpXmlRpc\Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationAuthority extends XmlRpcRequester implements RegistrationAuthorityInterface
{
    protected LoggerInterface $logger;

    protected array $entrypoint;

    protected Router $router;

    public function __construct(
        LoggerInterface $logger,
        Router $router,
        array $entrypoint,
        array $clientOptions
    ) {
        $this->logger = $logger;
        $this->router = $router;
        $this->entrypoint = $entrypoint;
        parent::__construct($clientOptions);
    }

    public function getUrl(): string
    {
        return $this->entrypoint['ra'];
    }

    private function call(string $method, $args): mixed
    {
        $response = null;

        try {
            $response = $this->send($method, $args);
            $this->logger->info(sprintf('[Universign - %s] SUCCESS', $method));
        } catch (Exception $e) {
            $this->logger->error(sprintf(
                '[Universign - %s] ERROR (%s): %s',
                $method,
                $e->getCode(),
                $e->getMessage()
            ));
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

    public function getCertificateAgreement(string $email): mixed
    {
        return $this->call('ra.getCertificateAgreement', $email);
    }

    public function revokeCertificate(string $emailOrPhoneNumber): mixed
    {
        return $this->call('ra.revokeCertificate', $emailOrPhoneNumber);
    }

    public function revokeMyCertificate(string $emailOrPhoneNumber): mixed
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

            $this->logger->info(sprintf(
                '[Universign - validate] define default callback URL : "%s"',
                $validationRequest->getCallbackURL()
            ));
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
