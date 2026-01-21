<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class UnknownStateException extends Exception
{
    public function __construct(string $stateClass, string $message = '', int $code = 0, ?Exception $previous = null)
    {
        $message = $message ?: "StateHandler class - {$stateClass} not found";
        parent::__construct($message, $code, $previous);
    }
}
