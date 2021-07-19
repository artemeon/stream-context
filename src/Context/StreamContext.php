<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Context;

use Artemeon\StreamContext\Exception\FileStreamException;

abstract class StreamContext
{
    /**
     * @param Resource Context resource created by stream_context_create()
     * @throws FileStreamException
     */
    public function createStreamContext()
    {
        $resource = stream_context_create($this->getContextOptions());

        if (!is_resource($resource)) {
            throw FileStreamException::fromMessage("Can't create stream context for: " . __CLASS__);
        }

        return $resource;
    }

    abstract protected function getContextOptions(): array;
}