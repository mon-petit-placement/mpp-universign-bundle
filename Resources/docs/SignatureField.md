SignatureField and DocSignatureField
====================================

The signature field structure allows you to define where the signature will be generated into your document.
There is two way to do that:

## SignatureField

This structure contains basic parameters in order to insert the `signatureField`.

Here is an example:

```php
...
'SignatureField' => [
    'name' => 'Client:',
    'page' => -1, // the -1 value is to point the last page, page are enumerated starting at 1
    'x' => 100,
    'y' => 400,
]
...
```
The `name` parameter is the name of the signature field. The Universign API can determine approximatively the signature field.
The `page` parameter is to define where is the signature field.
The `x` and `y` parameter is the coordinate of the signature field, is not necessary if you use the parameter `name`.

## DocSignatureField

This structure is the same as `SignatureField` with extra parameters.
This strucure is used to create a signature field into the document.

Here is an example:
```php
...
'SignatureField' => [
    'name' => 'Client:',
    'page' => -1, // the -1 value is to point the last page, page are enumerated starting at 1
    'x' => 100,
    'y' => 400,
    'signerIndex' => 0,
    'image' => $contentBase64,
]
...
```
The `image` parameter is your signature template. By default it will use the Universign template one. You can use your own image with this parameter encoded in `base64`.
