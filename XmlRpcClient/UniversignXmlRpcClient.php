<?php

namespace Mpp\UniversignBundle\XmlRpcClient;

use Laminas\XmlRpc\Client;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniversignXmlRpcClient
{
    /**
     * @var Client
     */
    private $xmlrpcClient;

    /**
     * @var string
     */
    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;
        // utiliser un singleton
        $this->xmlrpcClient = new Client($this->getURL());
    }

    /**
     * @return string;
     */
    private function getUrl()
    {
        return $this->url;
    }

    /**
     * @param OptionResolver $resolver
     *
     * @return array<mixed>
     */
    private function configureFilter($resolver)
    {
        $resolver
            ->setDefined('TransactionFilter')->setAllowedTypes('TransactionFilter', ['array'])->setNormalizer('TransactionFilter', function (Options $option, $values) {
                $resolver = new OptionsResolver();

                $resolver
            ->setDefined('requesterEmail')->setAllowedTypes('requesterEmail', ['string'])
            ->setDefined('profile')->setAllowedTypes('profile', ['string'])
            ->setDefined('notBefore')->setAllowedTypes('notBefore', ['DateTime'])->setNormalizer('notBefore', function (Options $option, $value) {
                $value = $value->format('Ymd\TH:i:s');
                xmlrpc_set_type($value, 'datetime');

                return $value;
            })
            ->setDefined('notAfter')->setAllowedTypes('notAfter', ['DateTime'])->setNormalizer('notAfter', function (Options $option, $value) {
                $value = $value->format('Ymd\TH:i:s');
                xmlrpc_set_type($value, 'datetime');

                return $value;
            })
            ->setDefined('startRange')->setAllowedTypes('startRange', ['int'])
            ->setDefined('stopRange')->setAllowedTypes('stopRange', ['int'])
            ->setDefined('signerId')->setAllowedTypes('signerId', ['string'])
            ->setDefined('notBeforeCompletion')->setAllowedTypes('notBeforeCompletion', ['DateTime'])->setNormalizer('notBeforeCompletion', function (Options $option, $value) {
                $value = $value->format('Ymd\TH:i:s');
                xmlrpc_set_type($value, 'datetime');

                return $value;
            })
            ->setDefined('notAfterCompletion')->setAllowedTypes('notAfterCompletion', ['DateTime'])->setNormalizer('notAfterCompletion', function (Options $option, $value) {
                $value = $value->format('Ymd\TH:i:s');
                xmlrpc_set_type($value, 'datetime');

                return $value;
            })
            ->setDefined('status')->setAllowedTypes('status', ['int'])
            ->setDefined('withAffiliated')->setAllowedTypes('withAffiliated', ['bool'])
            ;
            })
        ;
    }

    private function resolveThirdParty($value)
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setRequired('name')->setAllowedTypes('name', ['string'])
            ->setRequired('address')->setAllowedTypes('address', ['string'])
            ->setRequired('postalCode')->setAllowedTypes('postalCode', ['string'])
            ->setRequired('city')->setAllowedTypes('city', ['string'])
            ->setRequired('country')->setAllowedTypes('country', ['string'])
        ;

        return $resolver->resolve($value);
    }

    private function resolveSepaData($value)
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setRequired('rum')->setAllowedTypes('rum', ['string'])
            ->setRequired('ics')->setAllowedTypes('ics', ['string'])
            ->setRequired('iban')->setAllowedTypes('iban', ['string'])
            ->setRequired('bic')->setAllowedTypes('bic', ['string'])
            ->setRequired('recurring')->setAllowedTypes('recurring', ['bool'])
            ->setRequired('debtor')->setAllowedTypes('debtor', ['array'])->setNormalizer('debtor', function (Options $option, $value) {
                return $this->resolveThirdParty($value);
            })
            ->setRequired('creditor')->setAllowedTypes('creditor', ['array'])->setNormalizer('creditor', function (Options $option, $value) {
                return $this->resolveThirdParty($value);
            })
        ;

        return $resolver->resolve($value);
    }

    private function resolveDocSignatureField($value)
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setDefined('name')->setAllowedTypes('name', ['string'])
            ->setDefined('page')->setAllowedTypes('page', ['int'])
            ->setDefined('x')->setAllowedTypes('x', ['int'])
            ->setDefined('y')->setAllowedTypes('y', ['int'])
            ->setRequired('signerIndex')->setAllowedTypes('signerIndex', ['int'])
        ;

        return $resolver->resolve($value);
    }

    /**
     * @param array<mixed> $values
     *
     * @return array<mixed> $values
     */
    private function resolveSigners($values)
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefined('firstname')->setAllowedTypes('firstname', ['string'])
            ->setDefined('lastname')->setAllowedTypes('lastname', ['string'])
            ->setDefined('organization')->setAllowedTypes('organization', ['string'])
            ->setDefined('emailAddress')->setAllowedTypes('emailAddress', ['string'])
            ->setDefined('phoneNum')->setAllowedTypes('phoneNum', ['string'])
            ->setDefined('profile')->setAllowedTypes('profile', ['string'])
            ->setDefined('language')->setAllowedTypes('language', ['string'])
            ->setDefined('role')->setAllowedTypes('role', ['string'])
            ->setDefined('validationSessionId')->setAllowedTypes('validationSessionId', ['string'])
            ->setDefined('birthDate')->setAllowedTypes('birthDate', ['DateTime'])->setNormalizer('birthDate', function (Options $option, $value) {
                $value = $value->format('Ymd\TH:i:s');
                xmlrpc_set_type($value, 'datetime');

                return $value;
            })
            ->setDefined('universignId')->setAllowedTypes('universignId', ['string'])
            ->setDefined('certificateType')->setAllowedTypes('certificateType', ['string'])
            ->setDefined('idDocuments')->setAllowedTypes('idDocuments', ['array '])->setNormalizer('idDocuments', function (Options $option, $value) {
                $resolver = new OptionsResolver();

                $resolver
                    ->setDefined('documents')->setAllowedTypes('documents', ['array'])->setNormalizer('documents', function (Options $option, $values) {
                        $b64Array = [];

                        foreach ($values as $value) {
                            $file = file_get_contents($value);
                            $b64 = new \Laminas\XmlRpc\Value\Base64($file);
                            array_push($b64Array, $b64);
                        }

                        return $b64Array;
                    })
                    ->setDefined('type')->setAllowedTypes('type', ['string'])
                ;

                return $resolver->resolve($value);
            })
        ;

        $resolvedParameter = [];

        foreach ($values as $value) {
            $resolvedParameter[] = $resolver->resolve($value);
        }

        return $resolvedParameter;
    }

    /**
     * @param array<mixed> $values
     *
     * @return array<mixed>
     */
    private function resolveDocuments($values)
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefined('title')->setAllowedTypes('title', ['string'])
            ->setDefined('documentType')->setAllowedTypes('documentType', ['string'])
            ->setDefined('content')->setAllowedTypes('content', ['string'])->setNormalizer('content', function (Options $option, $value) {
                $file = file_get_contents($value);
                $b64 = new \Laminas\XmlRpc\Value\Base64($file);

                return $b64;
            })
            ->setDefined('checkBoxTexts')->setAllowedTypes('checkBoxTexts', ['array'])
            ->setDefined('url')->setAllowedTypes('url', ['string'])
            ->setDefined('fileName')->setAllowedTypes('fileName', ['string'])
            ->setDefined('DocSignatureField')->setAllowedTypes('DocSignatureField', ['array'])->setNormalizer('DocSignatureField', function (Options $option, $value) {
                return $this->resolveDocSignatureField($value);
            })
            ->setDefined('SEPAData')->setAllowedTypes('SEPAData', ['array'])->setNormalizer('SEPAData', function (Options $option, $value) {
                return $this->resolveSepaData($value);
            })
        ;

        $resolvedParameter = [];

        foreach ($values as $value) {
            $resolvedParameter[] = $resolver->resolve($value);
        }

        return $resolvedParameter;
    }

    private function configureRedirectionCallback($values)
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefined('URL')->setAllowedTypes('URL', ['string'])
            ->setDefined('displayName')->setAllowedTYpes('displayNames', ['string'])
        ;

        return $resolver->resolve($values);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return array<mixed>
     */
    private function configureSignatureParameters($resolver)
    {
        $resolver
            ->setRequired('signers')->setAllowedTypes('signers', ['array'])->setNormalizer('signers', function (Options $option, $values) {
                return $this->resolveSigners($values);
            })
            ->setRequired('documents')->setAllowedTypes('documents', ['array'])->setNormalizer('documents', function (Options $option, $values) {
                return $this->resolveDocuments($values);
            })
            ->setDefined('finalDocSent')->setAllowedTypes('finalDocSent', ['bool'])
            ->setDefined('profile')->setAllowedTypes('profile', ['string'])
            ->setDefined('finalDocRequesterSent')->setAllowedTypes('finalDocRequesterSent', ['bool'])
            ->setDefined('mustContactFirstSigner')->setAllowedTypes('mustContactFirstSigner', ['bool'])
            ->setDefined('finalDocObserverSent')->setAllowedTypes('finalDocObserverSent', ['bool'])
            ->setDefined('description')->setAllowedTypes('description', ['string'])
            ->setDefined('certificateType')->setAllowedTypes('certificateType', ['string'])
            ->setDefined('language')->setAllowedTypes('language', ['string'])
            ->setDefined('handwrittenSignatureMode')->setAllowedTypes('handwrittenSignatureMode', ['int'])
            ->setDefined('failRedirection')->setAllowedTypes('failRedirection', ['array'])->setNormalizer('failRedirection', function(Options $option, $values) {
                return $this->configureRedirectionCallback($values);
            })
            ->setDefined('cancelRedirection')->setAllowedTypes('cancelRedirection', ['array'])->setNormalizer('cancelRedirection', function(Options $option, $values) {
                return $this->configureRedirectionCallback($values);
            })
            ->setDefined('successRedirection')->setAllowedTypes('successRedirection', ['array'])->setNormalizer('successRedirection', function(Options $option, $values) {
                return $this->configureRedirectionCallback($values);
            })
            ->setDefined('chainingMode')->setAllowedTypes('chainingMode', ['string'])
            ->setDefined('finalDocCCeMails')->setAllowedTypes('finalDocCCeMails', ['array'])
            ->setDefined('twoStepsRegistration')->setAllowedTypes('twoStepsRegistration', ['bool'])
            ->setDefined('registrationCallbackURL')->setAllowedTypes('registrationCallbackURL', ['string'])
        ;
    }

    /**
     * @param OptionResolver $resolver
     */
    private function resolveValidationRequest($resolver)
    {
        $resolver
            ->setRequired('idDocument')->setAllowedTypes('idDocument', ['array'])->setNormalizer('idDocument', function (Options $option, $values) {
                $resolver = new OptionsResolver();

                $resolver
                    ->setRequired('photos')->setAllowedTypes('photos', ['array'])
                    ->setRequired('type')->setAllowedTypes('type', ['int'])
                ;

                return $resolver->resolve($values);
            })
            ->setRequired('PersonalInfo')->setAllowedTypes('PersonalInfo', ['array'])->setNormalizer('PersonalInfo', function (Options $option, $values) {
                $resolver = new OptionsResolver();

                $resolver
                    ->setRequired('firstname')->setAllowedTypes('firstname', ['string'])
                    ->setRequired('lastname')->setAllowedTypes('lastname', ['string'])
                    ->setRequired('birthDate')->setAllowedTypes('birthDate', ['string'])
                ;

                return $resolver->resolve($values);
            })
            ->setDefined('allowManual')->setAllowedTypes('allowManual', ['bool'])
            ->setDefined('callbackURL')->setAllowedTypes('callbackURL', ['bool'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return array<mixed>
     */
    private function resolveMatching($resolver)
    {
        $resolver
            ->setRequired('lastname')->setAllowedTypes('lastname', ['string'])
            ->setRequired('firstname')->setAllowedTypes('firstname', ['string'])
            ->setDefined('mobile')->setAllowedTypes('mobile', ['string'])
            ->setDefined('email')->setAllowedTypes('email', ['string'])
        ;
    }

    /**
     * @param array<mixed> $matchingFilter
     *
     * @return array<mixed>
     */
    public function matchAccount($matchingFilter)
    {
        $resolver = new OptionsResolver();
        $this->resolveMatching($resolver);
        $resolvedMatchingFilter = $resolver->resolve($matchingFilter);

        return $this->xmlrpcClient->call(
            'matcher.matchAccount', [
                $resolvedMatchingFilter,
            ]
        );
    }

    /**
     * @param string $userEmail
     *
     * @return array<mixed>
     */
    public function getCertificatAgreement($userEmail)
    {
        return $this->xmlrpcClient
            ->call('ra.getCertificateAgreement', [
                $userEmail,
            ]
        );
    }

    /**
     * @param string userEmail
     */
    public function revokeCertificate($userEmail)
    {
        return $this->xmlrpcClient
            ->call('ra.revokeCertificate', [
                $userEmail,
            ]
        );
    }

    /**
     * @param string validationSessionId
     */
    public function getValidationResult($validationSessionId)
    {
        return $this->xmlrpcClient
            ->call('validator.getResult', [
                $validationSessionId,
            ]
        );
    }

    /**
     * @param array<mixed> $validationRequest
     */
    public function validateCertificate($validationRequest)
    {
        $resolver = new OptionsResolver();
        $this->resolveValidationRequest($resolver);
        $resolvedValidationRequest = $resolver->resolve($validationRequest);

        return $this->xmlrpcClient
            ->call('validator.validate',
                $resolvedValidationRequest
        );
    }

    /**
     * @param string $transactionId
     *
     * @return array<mixed>
     */
    public function getDocumentsTransaction($transactionId)
    {
        if (!empty($transactionId)) {
            return  $this
                ->xmlrpcClient
                ->call(
                    'requester.getDocuments', [
                         $transactionId,
                    ]
                )
            ;
        }

        return null;
    }

    /**
     * @param string $transactionId
     *
     * @return bool
     */
    public function relaunchTransaction($transactionId)
    {
        if (!empty($transactionId)) {
            $this
                ->xmlrpcClient
                ->call(
                    'requester.relaunchTransaction', [
                         $transactionId,
                    ]
                )
            ;

            return true;
        }

        return false;
    }

    /**
     * @param string $transactionId
     *
     * @return bool
     */
    public function cancelTransaction($transactionId)
    {
        if (!empty($transactionId)) {
            $this
                ->xmlrpcClient
                ->call(
                    'requester.cancelTransaction', [
                         $transactionId,
                    ]
                )
            ;

            return true;
        }

        return false;
    }

    /**
     * @param string $transactionId
     *
     * @return array<mixed>
     */
    public function getTransactionInfo($transactionId)
    {
        if (!empty($transactionId)) {
            return $this
                ->xmlrpcClient
                ->call(
                    'requester.getTransactionInfo', [
                         $transactionId,
                    ]
                )
            ;
        }

        return null;
    }

    /**
     * @param string
     *
     * @return mixed
     */
    public function signDocuments($path)
    {
        $file = file_get_contents($path);
        $b64 = new \Laminas\XmlRpc\Value\Base64($file);

        return $this
            ->xmlrpcClient
            ->call(
                'signer.sign', [
                    $b64,
                ]
            )
        ;
    }

    /**
     * @param array<mixed> $filter
     *
     * @return array<mixed>
     */
    public function listTransaction($filter)
    {
        $resolver = new OptionsResolver();
        $this->configureFilter($resolver);
        $resolvedParameters = $resolver->resolve($filter);

        return $this
            ->xmlrpcClient
            ->call(
                'requester.requestTransaction', [
                     $resolvedParameters,
                ]
            )
        ;
    }

    /**
     * @param array<mixed> $parameters
     *
     * @return string
     */
    public function initTransaction($parameters)
    {
        $resolver = new OptionsResolver();

        $this->configureSignatureParameters($resolver);
        $resolvedParameters = $resolver->resolve($parameters);

        file_put_contents('test.xml', xmlrpc_encode($resolvedParameters));
        $value = $this
            ->xmlrpcClient
            ->call(
                'requester.requestTransaction',
                     array($resolvedParameters)
            )
        ;

        return $value;
    }
}
