<?php

declare(strict_types=1);

namespace Artemeon\StreamContext;

use Artemeon\StreamContext\Exception\FileStreamException;
use Artemeon\StreamContext\Exception\StreamContextException;
use LogicException;
use RuntimeException;
use SplFileObject;

/**
 * Helper class to create an check file streams based on the given FileStream configuration.
 */
final class FileObjectFactory
{
    /**
     * Creates a streamable SplFileObject based on the given FileStream configuration.
     *
     * @throws FileStreamException
     */
    public static function create(FileStream $fileStream): SplFileObject
    {
        try {
            $file = new SplFileObject(
                $fileStream->getUrl(),
                $fileStream->getMode(),
                false,
                self::createStreamContext($fileStream),
            );
        } catch (LogicException | RuntimeException $e) {
            throw new FileStreamException($e->getMessage(), $e->getCode(), $e);
        }

        $isRemoteSource = preg_match("/^(?!file)\w+:\/\//", $fileStream->getUrl()) === 1;
        $hasFileExtension = preg_match("/\.\w+$/", $fileStream->getUrl()) === 1;

        // isReadable only works for local filesystems
        if (!$file->isReadable() && !$isRemoteSource) {
            throw FileStreamException::fromMessage("File: '{$fileStream->getUrl()}' is not readable");
        }

        // Enforce file extension check only for file's with an explizit extension
        if ($hasFileExtension && $fileStream->getFileExtension() !== '') {
            if ($file->getExtension() !== $fileStream->getFileExtension()) {
                throw new FileStreamException("'File extension must be lowercase: " . $fileStream->getFileExtension() . ', given: ' . $file->getExtension());
            }
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
