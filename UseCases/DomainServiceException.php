<?php

declare(strict_types=1);

namespace UseCases;

use Throwable;

class DomainServiceException extends ApiException
{
    public function __construct($causer, $causer_params = null, Throwable $previous = null)
    {
        $message = "Domain service failed: $causer";
        parent::__construct($message, 500, $previous);
        $this->causer = $causer;
        $this->causer_params = $causer_params;
    }
}
