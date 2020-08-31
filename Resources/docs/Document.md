Document Object
===============

The `Document` object represent the real document to be signed.

## Adding Document
To create the object `Document` you need to call the static function `Document::createFromArray`
following, to add document you need to call the function `addDocument` on the `TransactionRequest` object.
you need to add a `documentName` to identify your document.

Here is an example:
```php
...

$transaction = $this
    ->container
    ->get('universign.requester')
    ->initiateTransaction()
;

$document = Document::createFromArray([
    'id' => 'ref181819',
    'documentType' => 'pdf',
    'content' => ['base64 content'],
    'fileName' => 'contract_test.pdf',
    'signatureField' => [
        'name' => 'Client:',
        'page' => 2,
        'signerIndex' => 0,
    ],
    'title' => 'foo title',
    'SEPAData' => [
    ],
    'checkBoxTexts' => [
        'foo',
        'bar',
        'foobar',
    ],
]);

$transaction->addDocument('documentName', $document);
```
For more information about the variable and their usage you can referer to the Universign API documentation on the section : `TransactionDocument`.

