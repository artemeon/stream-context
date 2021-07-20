<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Context;

use Artemeon\StreamContext\Exception\StreamContextException;

/**
 * Base class for ale protocol specific stream context options
 *
 * @since 0.1
 */
abstract class StreamContext
{
    /**
     * @param Resource Context resource created by stream_context_create()
     * @throws StreamContextException
     */
    public function createStreamContext()
    {
        $resource = stream_context_create($this->getContextOptions());

        if (!is_resource($resource)) {
            throw StreamContextException::fromMessage("Can't create stream context for: " . __CLASS__);
        }

        return $resource;
    }

    abstract protected function getContextOptions(): array;
}