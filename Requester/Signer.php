<?php

namespace Mpp\UniversignBundle\Requester;

use Laminas\XmlRpc\Client;
use Laminas\XmlRpc\Client\Exception\FaultException;
use Laminas\XmlRpc\Value\Base64;
use Laminas\XmlRpc\Value\DateTime;
use Mpp\UniversignBundle\Model\InitiatorInfo;
use Mpp\UniversignBundle\Model\RedirectionConfig;
use Mpp\UniversignBundle\Model\SignerInfo;
use Mpp\UniversignBundle\Model\SignOptions;
use Mpp\UniversignBundle\Model\TransactionInfo;
use Mpp\UniversignBundle\Model\TransactionRequest;
use Mpp\UniversignBundle\Model\TransactionResponse;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Signer extends XmlRpcRequester implements SignerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var array
     */
    protected $entrypoint;

    /**
     * @var array
     */
    protected $options;

    public function __construct(LoggerInterface $logger, Router $router, array $entrypoint, array $options, array $clientOptions)
    {
        $this->logger = $logger;
        $this->router = $router;
        $this->entrypoint = $entrypoint;
        $this->options = $options;

        parent::__construct($clientOptions);
    }

    /**
     * @return string;
     */
    public function getUrl()
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

        $transaction = TransactionRequest::createFromArray(array_merge($defaultOptions, $options));

        // TODO: Add redirections on signers ?

        return $transaction;
    }

    /**
     * {@inheritdoc}
     */
    public function requestTransaction(TransactionRequest $transactionRequest): TransactionResponse
    {
        $transactionResponse = new TransactionResponse();

        $flattenedTransactionRequest = self::flatten($transactionRequest);
        $flattenedTransactionRequest['documents'] = array_values($flattenedTransactionRequest['documents']);

        try {
            $response = $this->xmlRpcClient->call('requester.requestTransaction', [$flattenedTransactionRequest]);
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
    public function getDocuments(string $documentId): array
    {
        $documents = [];

        try {
            $documents = $this->xmlRpcClient->call('requester.getDocuments', $documentId);
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
            $documents = $this->xmlRpcClient->call('requester.getDocumentsByCustomId', $customId);
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
            $response = $this->xmlRpcClient->call('requester.getTransactionInfo', $transactionId);
            $this->logger->info('[Universign - requester.getTransactionInfo] SUCCESS');
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
                ->setRedirectWait($response['redirectWait'] ?? null)
            ;
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
            $response = $this->xmlRpcClient->call('requester.getTransactionInfoByCustomId', $customId);
            $this->logger->info('[Universign - requester.getTransactionInfoByCustomId] SUCCESS');
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
                ->setRedirectWait($response['redirectWait'] ?? null)
            ;
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
            $response = $this->xmlRpcClient->call('requester.relaunchTransaction', $transactionId);
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
            $response = $this->xmlRpcClient->call('requester.cancelTransaction', $transactionId);
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
    public function sign(Base64 $document): ?string
    {
        $response = null;
        $data = [
            'document' => new Base64($document),
        ];

        try {
            $response = $this->xmlRpcClient->call('signer.sign', self::flatten($data));
            $this->logger->info('[Universign - signer.sign] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - signer.sign] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function signWithOptions(Base64 $document, SignOptions $options): ?string
    {
        $response = null;
        $data = [
            'document' => $document,
            'options' => $options,
        ];

        try {
            $response = $this->xmlRpcClient->call('signer.signWithOptions', self::flatten($data));
            $this->logger->info('[Universign - signer.signWithOptions] SUCCESS');
        } catch (FaultException $fe) {
            $this->logger->error(sprintf('[Universign - signer.signWithOptions] ERROR (%s): %s', $fe->getCode(), $fe->getMessage()));
        }

        return $response;
    }
}
