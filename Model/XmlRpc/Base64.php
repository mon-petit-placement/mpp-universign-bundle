<?php

namespace Mpp\UniversignBundle\Model\XmlRpc;

use PhpXmlRpc\Value;

class Base64 extends Value
{
    /**
     * @param string $value content encoded to base64
     */
    public function __construct(string $value)
    {
        parent::__construct($value, self::$xmlrpcBase64);
    }
}
