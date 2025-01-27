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

    /**
     * @return array<non-empty-string, array<non-empty-string, mixed>>
     */
    abstract protected function getContextOptions(): array;
}
