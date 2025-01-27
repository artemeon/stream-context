<?php

declare(strict_types=1);

namespace Artemeon\StreamContext;

use Artemeon\StreamContext\Exception\FileStreamException;
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
                filename: $fileStream->getUrl(),
                mode: $fileStream->getMode(),
                context: $fileStream->getStreamContext()?->createStreamContext(),
            );
        } catch (LogicException | RuntimeException $e) {
            throw new FileStreamException($e->getMessage(), $e->getCode(), $e);
        }

        $hasFileExtension = preg_match("/\.\w+$/", $fileStream->getUrl()) === 1;

        // Enforce file extension check only for files with an explicit extension.
        if ($hasFileExtension && !empty($fileStream->getFileExtension()) && $file->getExtension() !== $fileStream->getFileExtension()) {
            throw new FileStreamException("File extension must be lowercase: {$fileStream->getFileExtension()}, given: {$file->getExtension()}");
        }

        return $file;
    }
}
