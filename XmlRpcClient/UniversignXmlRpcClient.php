<?php

namespace Mpp\UniversignBundle\XmlRpcClient;

use DateTimeInterface;
use Laminas\XmlRpc\Client;
use Laminas\XmlRpc\AbstractValue;
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
     * @param OptionsResolver $resolver
     */
    private function configureSignatureParameters($resolver)
    {
        $resolver
            ->setRequired('signers')->setAllowedTypes('signers', ['array'])->setNormalizer('signers', function (Options $option, $values) {
                $resolver = new OptionsResolver();

                $resolver
                    ->setRequired('firstname')->setAllowedTypes('firstname', ['string'])
                    ->setRequired('lastname')->setAllowedTypes('lastname', ['string'])
                    ->setRequired('organization')->setAllowedTypes('organization', ['string'])
                    ->setRequired('emailAddress')->setAllowedTypes('emailAddress', ['string'])
                    ->setRequired('phoneNum')->setAllowedTypes('phoneNum', ['string'])
                    ->setRequired('language')->setAllowedTypes('language', ['string'])
                    ->setRequired('role')->setAllowedTypes('role', ['string'])
                    ->setDefined('birthDate')->setAllowedTypes('birthDate', ['DateTime'])->setNormalizer('birthDate', function (Options $option, $value) {
                        return $value->format(DateTimeInterface::ISO8601);
                    })
                    ->setRequired('universignId')->setAllowedTypes('universignId', ['string'])
                    ->setRequired('certificateType')->setAllowedTypes('certificateType', ['string'])
                    ->setRequired('validationSessionId')->setAllowedTypes('validationSessionId', ['string'])
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
                    ->setRequired('documentType')->setAllowedTypes('documentType', ['string'])
                    ->setRequired('url')->setAllowedTypes('url', ['string'])
                    ->setRequired('fileName')->setAllowedTypes('fileName', ['string'])
                    ->setDefined('DocSignatureField')->setAllowedTypes('DocSignatureField', ['array'])->setNormalizer('DocSignatureField', function (Options $option, $value) {
                        $resolver = new OptionsResolver();
                        $resolver
                            ->setDefined('name')->setAllowedTypes('name', ['string'])
                            ->setRequired('page')->setAllowedTypes('page', ['int'])
                            ->setRequired('x')->setAllowedTypes('x', ['int'])
                            ->setRequired('y')->setAllowedTypes('y', ['int'])
                            ->setRequired('signerIndex')->setAllowedTypes('signerIndex', ['int'])
                        ;

                        return $resolver->resolve($value);
                    })
                ;

                $resolvedParameter = [];

                foreach ($values as $value) {
                    $resolvedParameter[] = $resolver->resolve($value);
                }

                return $resolvedParameter;
            });
    }

    /**
     * @return string
     * @param array<mixed> $parameters
     */
    public function initiateConnexion($parameters)
    {
        $resolver = new OptionsResolver();

        $this->configureSignatureParameters($resolver);
        $resolvedParameters = $resolver->resolve($parameters);

        return $this
            ->xmlrpcClient
            ->call(
                'requester.requestTransaction', [
                     $resolvedParameters
                ]
            );
    }
}
