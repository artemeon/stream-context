<?php

declare(strict_types=1);

namespace Artemeon\StreamContext;

use Artemeon\StreamContext\Context\StreamContext;

final class FileStream
{
    private string $mode = "r";

    public static function fromUrl(string $url, StreamContext $streamContext = null): self
    {

    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }
}