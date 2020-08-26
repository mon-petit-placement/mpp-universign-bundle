Signer Structure
================

The signer structure is used to add a signer.
Before add a signer you need to create it from the array parameters with the static function `Signer::createFromArray()`.

Here is an example:

```php
$transaction = $this
    ->container
    ->get('universign.requester')
    ->initiateTransaction()
;

$signer = Signer::createFromArray([
    'firstname' => 'john',
    'lastname' => 'doe',
    'organization' => 'dummy company',
    'emailAddress' => 'john.doe@dummy-company.com',
    'phoneNum' => '+0122334455',
    'language' => 'fr',
    'role' => \Mpp\UniversignBundle\Model\Signer::ROLE_SIGNER,
    'birthDate' => new \DateTime::createFromFormat('Y-m-d', '2000-01-01'),
    'certificateType' =>  \Mpp\UniversignBundle\Model\CertificateType::SIMPLE,
]);

$transaction->addSigner($signer);
```