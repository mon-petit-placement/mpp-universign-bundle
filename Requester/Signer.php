<?php

namespace Mpp\UniversignBundle\Requester;

use Mpp\UniversignBundle\Exception\FaultException;
use Mpp\UniversignBundle\Model\InitiatorInfo;
use Mpp\UniversignBundle\Model\RedirectionConfig;
use Mpp\UniversignBundle\Model\SignerInfo;
use Mpp\UniversignBundle\Model\SignOptions;
use Mpp\UniversignBundle\Model\TransactionInfo;
use Mpp\UniversignBundle\Model\TransactionRequest;
use Mpp\UniversignBundle\Model\TransactionResponse;
use PhpXmlRpc\Value;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Signer extends XmlRpcRequester implements SignerInterface
{
    protected LoggerInterface $logger;

    protected Router $router;

    protected array $entrypoint;

    protected array $options;

    public function __construct(LoggerInterface $logger, Router $router, array $entrypoint, array $options, array $clientOptions)
    {
        $this->logger = $logger;
        $this->router = $router;
        $this->entrypoint = $entrypoint;
        $this->options = $options;

        parent::__construct($clientOptions);
    }

    public function getUrl(): string
    {
        return $this->entrypoint['sign'];
    }

    /**
     * {@inheritdoc}
     */
    public function initiateTransactionRequest(array $options = []): TransactionRequest
    {
        $defaultOptions = [];

        if (null !== $this->options['registration_callback_route_name']) {
            $defaultOptions['registrationCallbackURL'] = $this->router->generate(
                $this->options['registration_callback_route_name'],
                $options['registration_callback_route_parameters'] ?? [],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            unset($options['registration_callback_route_parameters']);
        }

        if (null !== $this->options['success_redirection_route_name']) {
            $defaultOptions['successRedirection'] = RedirectionConfig::createFromArray([
                'URL' => $this->router->generate(
                    $this->options['success_redirection_route_name'],
                    $options['success_redirection_route_parameters'] ?? [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'displayName' => 'Success',
            ]);

            unset($options['success_redirection_route_parameters']);
        }

        if (null !== $this->options['cancel_redirection_route_name']) {
            $defaultOptions['cancelRedirection'] = RedirectionConfig::createFromArray([
                'URL' => $this->router->generate(
                    $this->options['cancel_redirection_route_name'],
                    $options['cancel_redirection_route_parameters'] ?? [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'displayName' => 'Cancel',
            ]);

            unset($options['cancel_redirection_route_parameters']);
        }

        if (null !== $this->options['fail_redirection_route_name']) {
            $defaultOptions['failRedirection'] = RedirectionConfig::createFromArray([
                'URL' => $this->router->generate(
                    $this->options['fail_redirection_route_name'],
                    $options['fail_redirection_route_parameters'] ?? [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'displayName' => 'Fail',
            ]);

            unset($options['fail_redirection_route_parameters']);
        }

        // TODO: Add redirections on signers ?

        return TransactionRequest::createFromArray(array_merge($defaultOptions, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function requestTransaction(TransactionRequest $transactionRequest): TransactionResponse
    {
        $transactionResponse = new TransactionResponse();

        try {
            $response = $this->call('requester.requestTransaction', [$transactionRequest]);
            $this->logger->info('[Universign - requester.requestTransaction] SUCCESS');
            $transactionResponse
                ->setId($response['id'])
                ->setUrl($response['url'])
                ->setState(TransactionResponse::STATE_SUCCESS)
            ;
        } catch (FaultException $fe) {
            $transactionResponse
                ->setState(TransactionResponse::STATE_ERROR)
                ->setErrorCode($fe->getCode())
                ->setErrorMessage($fe->getMessage())
            ;

            $this->logger->error(sprintf('[Universign - requester.requestTransaction] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));
        }

        return $transactionResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocuments(string $transactionId): array
    {
        $documents = [];

        try {
            $documents = $this->call('requester.getDocuments', $transactionId);
            $this->logger->info('[Universign - requester.getDocuments] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - requester.getDocuments] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));
        }

        return $documents;
    }

    /**
     * {@inheritdoc}
     */
    public function getDocumentsByCustomId(string $customId): array
    {
        $documents = [];

        try {
            $documents = $this->call('requester.getDocumentsByCustomId', $customId);
            $this->logger->info('[Universign - requester.getDocumentsByCustomId] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - requester.getDocumentsByCustomId] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));
        }

        return $documents;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionInfo(string $transactionId): TransactionInfo
    {
        $transactionInfo = new TransactionInfo();

        try {
            $response = $this->call('requester.getTransactionInfo', $transactionId);
            $this->logger->info('[Universign - requester.getTransactionInfo] SUCCESS');
            $this->buildTransactionInfo($transactionInfo, $response);
            foreach ($response['signerInfos'] as $signerInfo) {
                $transactionInfo->addSignerInfo(SignerInfo::createFromArray($signerInfo));
            }
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - requester.getTransactionInfo] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));

            $transactionInfo
                ->setState(TransactionInfo::STATE_ERROR)
                ->setErrorCode($fe->getCode())
                ->setErrorMessage($fe->getMessage())
            ;
        }

        return $transactionInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionInfoByCustomId(string $customId): TransactionInfo
    {
        $transactionInfo = new TransactionInfo();

        try {
            $response = $this->call('requester.getTransactionInfoByCustomId', $customId);
            $this->logger->info('[Universign - requester.getTransactionInfoByCustomId] SUCCESS');
            $this->buildTransactionInfo($transactionInfo, $response);
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - requester.getTransactionInfoByCustomId] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));

            $transactionInfo
                ->setState(TransactionInfo::STATE_ERROR)
                ->setErrorCode($fe->getCode())
                ->setErrorMessage($fe->getMessage())
            ;
        }

        return $transactionInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function relaunchTransaction(string $transactionId): TransactionInfo
    {
        $transactionInfo = new TransactionInfo();

        try {
            $this->call('requester.relaunchTransaction', $transactionId);
            $this->logger->info('[Universign - requester.relaunchTransaction] SUCCESS');
            $transactionInfo->setState(TransactionInfo::STATE_SUCCESS);
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - requester.relaunchTransaction] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));

            $transactionInfo
                ->setState(TransactionInfo::STATE_ERROR)
                ->setErrorCode($fe->getCode())
                ->setErrorMessage($fe->getMessage())
            ;
        }

        return $transactionInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function cancelTransaction(string $transactionId): TransactionInfo
    {
        $transactionInfo = new TransactionInfo();

        try {
            $this->call('requester.cancelTransaction', $transactionId);
            $this->logger->info('[Universign - requester.cancelTransaction] SUCCESS');
            $transactionInfo->setState(TransactionInfo::STATE_SUCCESS);
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - requester.cancelTransaction] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));

            $transactionInfo
                ->setState(TransactionInfo::STATE_ERROR)
                ->setErrorCode($fe->getCode())
                ->setErrorMessage($fe->getMessage())
            ;
        }

        return $transactionInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function sign(string $document): ?string
    {
        $response = null;
        $data = [
            'document' => new Value($document, 'base64'),
        ];

        try {
            $response = $this->call('signer.sign', $data);
            $this->logger->info('[Universign - signer.sign] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - signer.sign] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function signWithOptions(string $document, SignOptions $options): ?string
    {
        $response = null;
        $data = [
            'document' => new Value($document, 'base64'),
            'options' => $options,
        ];

        try {
            $response = $this->call('signer.signWithOptions', $data);
            $this->logger->info('[Universign - signer.signWithOptions] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - signer.signWithOptions] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));
        }

        return $response;
    }

    public function buildTransactionInfo(TransactionInfo $transactionInfo, array $response): void
    {
        $transactionInfo
            ->setState(TransactionInfo::STATE_SUCCESS)
            ->setStatus($response['status'] ?? null)
            ->setCurrentSigner($response['currentSigner'] ?? null)
            ->setCreationDate(\DateTime::createFromFormat('Ymd\TH:i:s', $response['creationDate']) ?? null)
            ->setDescription($response['description'] ?? null)
            ->setInitiatorInfo(InitiatorInfo::createFromArray($response['initiatorInfo']) ?? null)
            ->setEachField($response['eachField'] ?? null)
            ->setCustomerId($response['customerId'] ?? null)
            ->setTransactionId($response['transactionId'] ?? null)
            ->setRedirectPolicy($response['redirectPolicy'] ?? null)
            ->setRedirectWait($response['redirectWait'] ?? null);
    }
}
