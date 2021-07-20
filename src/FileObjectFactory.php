<?php

declare(strict_types=1);

namespace Artemeon\StreamContext;

use Artemeon\StreamContext\Exception\FileStreamException;
use Artemeon\StreamContext\Exception\StreamContextException;
use SplFileObject;

/**
 * Helper class to create an check file streams based on the given FileStream configuration
 *
 * @since 0.1
 */
final class FileObjectFactory
{
    /**
     * Creates a streamable SplFileObject based on the given FileStream configuration
     *
     * @throws FileStreamException
     */
    public static function create(FileStream $fileStream): SplFileObject
    {
        $file = new SplFileObject(
            $fileStream->getUrl(),
            $fileStream->getMode(),
            false,
            self::createStreamContext($fileStream)

        );

        $isRemoteSource = preg_match("/^(?!file)\w+:\/\//", $fileStream->getUrl()) === 1;

        // isReadable only works for local filesystems
        if (!$file->isReadable() && !$isRemoteSource) {
            throw FileStreamException::fromMessage("File: '{$fileStream->getUrl()}' is not readable");
        }

        if ($file->getExtension() !== $fileStream->getFileExtension() && $fileStream->getFileExtension() !== "") {
            throw new FileStreamException("File extension must be lowercase: " . $fileStream->getFileExtension());
        }

        return $file;
    }

    /**
     * @throws FileStreamException
     */
    private static function createStreamContext(FileStream $fileStream)
    {
        try {
            if ($fileStream->getStreamContext() === null) {
                return null;
            }

            return $fileStream->getStreamContext()->createStreamContext();
        } catch (StreamContextException $exception) {
            throw FileStreamException::fromMessage($exception->getMessage(), $exception);
        }
    }
}