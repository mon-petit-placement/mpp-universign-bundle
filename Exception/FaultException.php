<?php

namespace Mpp\UniversignBundle\Exception;

use PhpXmlRpc\Response;
use Throwable;

class FaultException extends \Exception
{
    public function __construct(string $method, Response $response, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Error (%s) when calling method \'%s\': %s',
                $response->faultCode(),
                $method,
                $response->faultString()
            ),
            $response->faultCode(),
            $previous
        );
    }
}