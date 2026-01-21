<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class UnknownCommandException extends Exception
{
    private string $commandName;

    public function __construct(string $commandName, string $message = '', int $code = 0, ?Exception $previous = null)
    {
        $this->commandName = $commandName;
        $message = $message ?: "Command {$commandName} not found";
        parent::__construct($message, $code, $previous);
    }

    public function getCommandName(): string
    {
        return $this->commandName;
    }
}
