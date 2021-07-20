<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Exception;

use Exception;

/**
 * @since 0.1
 */
class FileStreamException extends Exception
{
    public static function fromMessage(string $message, Exception $previous = null): self
    {
        return new self($message, 0, $previous);
    }
}