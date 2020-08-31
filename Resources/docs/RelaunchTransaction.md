Relaunch request
================

This request is used if the expiration date of the signature request is close to the deadline, you can restart the request on today's date.

To restart the signature request, simply send this request with the `transactionId`.

```php
$this->requester->relaunchTransaction($transactionId);
```