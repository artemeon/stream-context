<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Context;

use Artemeon\StreamContext\Exception\StreamContextException;

/**
 * Base class for ale protocol specific stream context options.
 */
abstract class StreamContext
{
    /**
     * @throws StreamContextException
     * @return resource Context resource created by stream_context_create()
     */
    public function createStreamContext(): mixed
    {
        $resource = stream_context_create($this->getContextOptions());

        if (!is_resource($resource)) {
            throw StreamContextException::fromMessage("Can't create stream context for: " . self::class);
        }

        return $resource;
    }

    abstract protected function getContextOptions(): array;
}
