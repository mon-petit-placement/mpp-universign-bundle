Symfony Bundle to quickly interact with Universign library
=================================================

Installation:
-------------

To install this bundle, simply run the following command:
```bash
$ composer require mpp/universign-bundle
```

How to use:
-------------

To use the Universign Bundle, you need to create a array with the data needed to create a numeric signature demand.
For example to send signatory information and documents, you must create a request of this type:

```php

        $request = [
            'signers' => [
                [
                    'firstname' => $faker->firstName,
                    'lastname' => $faker->lastName,
                    'organization' => $faker->company,
                    'emailAddress' => $faker->email,
                    'phoneNum' => $faker->phoneNumber,
                    'language' => 'fr',
                    'role' => 'signer',
                    'birthDate' => $faker->dateTimeBetween('-90 years', '-20 years'),
                    'certificateType' => 'simple',
                ],
            ],
            'documents' => [
                [
                    'documentType' => 'pdf',
                    'fileName' => 'contract_test',
                    'content' => './contract_test.pdf',
                    'DocSignatureField' => [
                        'name' => 'Client:',
                        'page' => 2,
                        'signerIndex' => 0,
                    ],
                    'SEPAData' => [
                        'rum' => '87654345678765',
                        'ics' => 'FR12ZZZ123456',
                        'iban' => 'FR7630006000011234567890189',
                        'bic' => 'BREDFRPPXXX',
                        'recurring' => false,
                        'debtor' => [
                            'name' => $faker->firstname.' '.$faker->lastname,
                            'address' => $faker->streetAddress,
                            'postalCode' => $faker->postcode,
                            'city' => $faker->city,
                            'country' => $faker->country,
                        ],
                        'creditor' => [
                            'name' => $faker->firstname.' '.$faker->lastName,
                            'address' => $faker->streetAddress,
                            'postalCode' => $faker->postcode,
                            'city' => $faker->city,
                            'country' => $faker->country,
                        ],
                    ],
                ],
            ],
            'finalDocSent' => true,
            'mustContactFirstSigner' => true,
            'description' => $faker->text(50),
            'language' => 'fr',
        ];

    return this->universignBundle->sendRequestTransaction($request);

```

you can refer to the [documentation of universign](https://help.universign.com/hc/fr/articles/360000837769-Guide-API-Universign) for all of the variables.

How to run:
-------------

Before run, you need to add the .env variable with the url and the credentials of the universign account.

For example: 
```dotenv
    https://user@mail.com:P@ssw0rd@sign.test.cryptolog.com/sign/rpc/
```

After this you can use this bundle on your Symfony Project.