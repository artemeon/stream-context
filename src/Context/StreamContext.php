<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Context;

/**
 * Base class for ale protocol specific stream context options.
 */
abstract class StreamContext
{
    /**
     * @return resource
     */
    public function createStreamContext(): mixed
    {
        return stream_context_create($this->getContextOptions());
    }

    abstract protected function getContextOptions(): array;
}
