<?php

declare(strict_types=1);

namespace Artemeon\StreamContext;

use Artemeon\StreamContext\Context\StreamContext;

/**
 * Configuration DTO for the file url and optional StreamContext options
 *
 * @since 0.1
 */
final class FileStream
{
    private string $url;
    private string $mode = "r";
    private ?StreamContext $streamContext;
    private string $fileExtension = '';

    private function __construct(string $url, ?StreamContext $streamContext)
    {
        $this->url = $url;
        $this->streamContext = $streamContext;
    }

    /**
     * Named constructor to create an instance base on the given streaming url and context parameters
     */
    public static function fromUrl(string $url, StreamContext $streamContext = null): self
    {
        return new self($url, $streamContext);
    }

    /**
     * @param string $mode Standard mode id read only, use this method to change file modes supporte by the used stream wrapper
     * @see https://www.php.net/manual/de/function.fopen.php
     */
    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @param string $fileExtension Enforce file extension for security
     */
    public function enforceFileExtension(string $fileExtension): void
    {
        $this->fileExtension = $fileExtension;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function getStreamContext(): ?StreamContext
    {
        return $this->streamContext;
    }

    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }
}