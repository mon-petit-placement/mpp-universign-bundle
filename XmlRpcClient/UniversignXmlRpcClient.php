<?php

namespace Mpp\UniversignBundle\XmlRpcClient;

use DateTimeInterface;
use Laminas\XmlRpc\Client;
use Laminas\XmlRpc\AbstractValue;
use Namshi\JOSE\Base64\Base64UrlSafeEncoder;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationRequest
{

    const TYPE_ID_CARD_FR = 'id_card_fr';

    const TYPE_PASSPORT_EU = 'passport_eu';

    const TYPE_TITRE_SEJOUR = 'titre_sejour';

    const TYPE_DRIVE_LICENSE = 'drive_license';

    protected $attributesDefinitions = [
        'documents' => 'array',
        'type' => 'string',
    ];
}

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

    /**
     * @param OptionsResolver $resolver
     * @return array<mixed>
     */
    private function configureSignatureParameters($resolver)
    {
        $resolver
            ->setRequired('signers')->setAllowedTypes('signers', ['array'])->setNormalizer('signers', function (Options $option, $values) {
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
                    ->setDefined('failURL')->setAllowedTypes('failURL', ['string'])
                    ->setDefined('cancelURL')->setAllowedTypes('cancelURL', ['string'])
                    ->setDefined('successURL')->setAllowedTypes('successURL', ['string'])
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
                            ->setDefined('documents')->setAllowedTypes('documents', ['array'])
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
            })
            ->setRequired('documents')->setAllowedTypes('documents', ['array'])->setNormalizer('documents', function (Options $option, $values) {
                $resolver = new OptionsResolver();

                $resolver
                    ->setDefined('documentType')->setAllowedTypes('documentType', ['string'])
                    ->setDefined('content')->setAllowedTypes('content', ['string'])->setNormalizer('content', function(Options $option, $value) {
                        $tmp = file_get_contents($value);
                        xmlrpc_set_type($tmp, 'base64');
                        return $tmp;
                    })
                    ->setDefined('url')->setAllowedTypes('url', ['string'])
                    ->setDefined('fileName')->setAllowedTypes('fileName', ['string'])
                    ->setDefined('DocSignatureField')->setAllowedTypes('DocSignatureField', ['array'])->setNormalizer('DocSignatureField', function (Options $option, $value) {
                        $resolver = new OptionsResolver();
                        $resolver
                            ->setDefined('name')->setAllowedTypes('name', ['string'])
                            ->setDefined('page')->setAllowedTypes('page', ['int'])
                            ->setDefined('x')->setAllowedTypes('x', ['int'])
                            ->setDefined('y')->setAllowedTypes('y', ['int'])
                            ->setDefined('signerIndex')->setAllowedTypes('signerIndex', ['int'])
                        ;

                        return $resolver->resolve($value);
                    })
                    ->setDefined('SEPAData')->setAllowedTypes('SEPAData', ['array'])->setNormalizer('SEPAData', function (Options $option, $value) {
                        $resolver = new OptionsResolver();

                        $resolver
                            ->setRequired('rum')->setAllowedTypes('rum', ['string'])
                            ->setRequired('ics')->setAllowedTypes('ics', ['string'])
                            ->setRequired('iban')->setAllowedTypes('iban', ['string'])
                            ->setRequired('bic')->setAllowedTypes('bic', ['string'])
                            ->setRequired('recurring')->setAllowedTypes('recurring', ['bool'])
                            ->setRequired('debtor')->setAllowedTypes('debtor', ['array'])->setNormalizer('debtor', function (Options $option, $value) {
                                $resolver = new OptionsResolver();

                                $resolver
                                    ->setRequired('name')->setAllowedTypes('name', ['string'])
                                    ->setRequired('address')->setAllowedTypes('address', ['string'])
                                    ->setRequired('postalCode')->setAllowedTypes('postalCode', ['string'])
                                    ->setRequired('city')->setAllowedTypes('city', ['string'])
                                    ->setRequired('country')->setAllowedTypes('country', ['string'])
                                ;

                                return $resolver->resolve($value);
                            })
                            ->setRequired('creditor')->setAllowedTypes('creditor', ['array'])->setNormalizer('creditor', function (Options $option, $value) {
                                $resolver = new OptionsResolver();

                                $resolver
                                    ->setRequired('name')->setAllowedTypes('name', ['string'])
                                    ->setRequired('address')->setAllowedTypes('address', ['string'])
                                    ->setRequired('postalCode')->setAllowedTypes('postalCode', ['string'])
                                    ->setRequired('city')->setAllowedTypes('city', ['string'])
                                    ->setRequired('country')->setAllowedTypes('country', ['string'])
                                ;

                                return $resolver->resolve($value);
                            })
                        ;

                            return $resolver->resolve($value);
                    })
                ;

                $resolvedParameter = [];

                foreach ($values as $value) {
                    $resolvedParameter[] = $resolver->resolve($value);
                }

                return $resolvedParameter;
            })
            ->setDefined('finalDocSent')->setAllowedTypes('finalDocSent', ['bool'])
            ->setDefined('finalDocRequesterSent')->setAllowedTypes('finalDocRequesterSent', ['bool'])
            ->setDefined('mustContactFirstSigner')->setAllowedTypes('mustContactFirstSigner', ['bool'])
            ->setDefined('finalDocObserverSent')->setAllowedTypes('finalDocObserverSent', ['bool'])
            ->setDefined('description')->setAllowedTypes('description', ['string'])
            ->setDefined('certificateType')->setAllowedTypes('certificateType', ['string'])
            ->setDefined('language')->setAllowedTypes('language', ['string'])
            ->setDefined('handwrittenSignatureMode')->setAllowedTypes('handwrittenSignatureMode', ['int'])
            ->setDefined('chainingMode')->setAllowedTypes('chainingMode', ['string'])
            ->setDefined('finalDocCCeMails')->setAllowedTypes('finalDocCCeMails', ['array'])
            ->setDefined('twoStepsRegistration')->setAllowedTypes('twoStepsRegistration', ['bool'])
            ;
    }

    /**
     * @param string $transactionId
     * @return array<mixed>
     */
    public function GetDocumentsTransaction($transactionId)
    {
        if(!empty($transactionId)) {

            $toto =  $this
                ->xmlrpcClient
                ->call(
                    'requester.getDocuments', [
                         $transactionId
                    ]
                );
                // dump("FILE FILE FILE");
                // dump($toto);
                $file = "test.pdf";
                file_put_contents($file, $toto[0]['content']);
                return $toto;
        }

        return null;
    }

    /**
     * @param string $transactionId
     * @return bool
     */
    public function relaunchTransaction($transactionId)
    {
        if(!empty($transactionId)) {

            $this
                ->xmlrpcClient
                ->call(
                    'requester.relaunchTransaction', [
                         $transactionId
                    ]
                );

            return true;
        }

        return false;
    }

    /**
     * @param string $transactionId
     * @return bool
     */
    public function cancelTransaction($transactionId)
    {
        if(!empty($transactionId)) {

            $this
                ->xmlrpcClient
                ->call(
                    'requester.cancelTransaction', [
                         $transactionId
                    ]
                );

            return true;
        }

        return false;
    }

    /**
     * @param string $transactionId
     * @return array<mixed>
     */
    public function getTransactionInfo($transactionId)
    {
        if(!empty($transactionId)) {

            return $this
                ->xmlrpcClient
                ->call(
                    'requester.getTransactionInfo', [
                         $transactionId
                    ]
                );
        }

        return null;
    }
    /**
     * @param string
     * @return string
     */
    public function signDocuments($url) {
        $document = file_get_contents('http://africau.edu/images/default/sample.pdf');
        $byte = $document;
            $request = [
                'byte' => [
                    $byte,
                ]
                ];
            $result =  $this
                ->xmlrpcClient
                ->call(
                    'signer.sign', [
                         $request
                    ]
                );
        dump($result);


    }

    /**
     * @param array<mixed> $filter
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
                     $resolvedParameters
                ]
            );
    }

    /**
     * @param array<mixed> $parameters
     * @return string
     */
    public function InitTransaction($parameters)
    {
        $resolver = new OptionsResolver();

        $this->configureSignatureParameters($resolver);
        $resolvedParameters = $resolver->resolve($parameters);
        dump($resolvedParameters);
        $test =  $this
            ->xmlrpcClient
            ->call(
                'requester.requestTransaction', [
                     $resolvedParameters
                ]
            );
        dump($test);
        return $test;
    }
}
