Transaction Filter
==================

**[⬆ back to readme](../../README.md)**

Requests the list of transactions matching the given filter. At most 1000
results are returned: to have more results, use multiple requests and ranges
in TransactionFilter.

Here is an example:
```php
...
use Mpp\UniversignBundle\Requester\RequesterInterface;

...
/**
 * @var RequesterInterface;
 */
private $requester;

public function __construct(RequesterInterface $requester)
{
    $this->requester = $requester;
}

...
    $transaction = $this->requester->initiateFilter();

    $transactionFilter->addFilter([
        'requesterEmail' => 'john.doe@mail.com',
        'profile' => 'jdoe',
        'notBefore' => new \DateTime::createFromFormat('Y-m-d', '1970-01-01'),
        'notAfter' => new \DateTime::createFromFormat('Y-m-d', '2020-01-01'),
        'startRange' => '0',
        'stopRange' => '1000', //maximum value startRange + 1000
        'signerId' => 'bde1e661-a217-4d2b-a3ec-249c2e266dr3',
        'notBeforeCompletion' => new \DateTime::createFromFormat('Y-m-d', '1970-01-01'),
        'notAfterCompletion' => new \DateTime::createFromFormat('Y-m-d', '2020-01-01'),
        'status' => '0',
        'withAffiliated' => false,
    ]);

    $transactionFilterResponse = $this->requester->requestTransactionFilter($transactionFilter);
```