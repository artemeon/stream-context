<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Exception;

use Exception;

/**
 * Base class for all package related exceptions
 */
class FileStreamException extends Exception
{
    public static function fromMessage(string $message): self
    {
        return new self($message);
    }
}