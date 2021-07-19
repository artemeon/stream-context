<?php

declare(strict_types=1);

namespace Artemeon\StreamContext;

use Artemeon\StreamContext\Context\StreamContext;
use Artemeon\StreamContext\Exception\FileStreamException;
use SplFileObject;

final class FileObjectFactory
{
    /**
     * @throws FileStreamException
     */
    public static function forMode(string $mode, string $url,  StreamContext $streamContext = null, string $extension = ''): SplFileObject
    {
        $isRemoteSource = preg_match("/^(?!file)\w+:\/\//", $url) === 1;
        $file = new SplFileObject($url, $mode, false, $streamContext->createStreamContext());

        // isReadable only works for local filesystems
        if (!$file->isReadable() && !$isRemoteSource) {
            throw FileStreamException::fromMessage("File: '{$url}' is not readable");
        }

        if ($file->getExtension() !== $extension && $extension !== "") {
            throw new FileStreamException("'File extension must be lowercase 'csv'");
        }

        return $file;
    }

    public static function read(string $url, StreamContext $streamContext = null, string $extension = ''): SplFileObject
    {
        return self::forMode('r', $url, $streamContext, $extension);
    }

    public static function readAndWrite(string $url, StreamContext $streamContext = null, string $extension = ''): SplFileObject
    {
        return self::forMode('rw', $url, $streamContext, $extension);
    }

    public static function create(FileStream $fileStream): SplFileObject
    {

    }
}