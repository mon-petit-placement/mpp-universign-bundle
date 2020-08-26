Cancel request
==============

To cancel a signature request, you simply have to send a request with the `transactionId`.
```php
$this->container->get('universign.requester')->cancelTransaction($transactionId);
```