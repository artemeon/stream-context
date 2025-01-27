<?php

declare(strict_types=1);

use Artemeon\StreamContext\Exception\FileStreamException;
use Artemeon\StreamContext\FileObjectFactory;
use Artemeon\StreamContext\FileStream;

describe('FileObjectFactory', function (): void {
    test('create()', function (): void {
        $sut = FileObjectFactory::create(FileStream::fromUrl('php://memory'));

        expect($sut)
            ->toBeInstanceOf(SplFileObject::class)
            ->and($sut->getFilename())
            ->toBe('memory');
    });

    test('create() with local file and enforced file extension', function (): void {
        $sut = FileObjectFactory::create(
            FileStream::fromUrl('file://' . dirname(__DIR__) . '/fixtures/test.json')
                ->enforceFileExtension('json'),
        );

        expect($sut)
            ->toBeInstanceOf(SplFileObject::class)
            ->and($sut->getFilename())
            ->toBe('test.json');
    });

    test('create() with directory', function (): void {
        FileObjectFactory::create(
            FileStream::fromUrl('file://' . dirname(__DIR__)),
        );
    })->throws(FileStreamException::class);

    test('create() with incorrect file extension', function (): void {
        FileObjectFactory::create(
            FileStream::fromUrl('file://' . dirname(__DIR__) . '/fixtures/test.json')
                ->enforceFileExtension('html'),
        );
    })->throws(FileStreamException::class, 'File extension must be lowercase: html, given: json');

    test('create() with unreadable file', function (): void {
        $path = dirname(__DIR__) . '/fixtures/not-readable.json';
        touch($path);
        chmod($path, 222);

        FileObjectFactory::create(
            FileStream::fromUrl('file://' . dirname(__DIR__) . '/fixtures/not-readable.json'),
        );
    })
        ->throws(FileStreamException::class)
        ->after(function (): void {
            unlink(dirname(__DIR__) . '/fixtures/not-readable.json');
        });
});
