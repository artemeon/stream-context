<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Exception;

use Exception;

final class StreamContextException extends Exception
{
    public static function fromMessage(string $message): self
    {
        return new self($message);
    }
}
