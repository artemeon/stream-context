<?php

declare(strict_types=1);

use Artemeon\StreamContext\Context\HttpStreamContext;
use Artemeon\StreamContext\FileStream;

use function Pest\Faker\fake;

covers(FileStream::class);
describe('FileStream', function (): void {
    test('fromUrl()', function (): void {
        $url = fake()->url();

        $sut = FileStream::fromUrl($url);

        expect($sut)
            ->toBeInstanceOf(FileStream::class)
            ->and($sut->getUrl())
            ->toBe($url)
            ->and($sut->getStreamContext())
            ->toBeNull();
    });

    test('enforceFileExtension()', function (): void {
        $extension = fake()->fileExtension();

        $sut = FileStream::fromUrl(fake()->url());
        $sut->enforceFileExtension($extension);

        expect($sut->getFileExtension())
            ->toBe($extension);
    });

    test('setMode()', function (): void {
        $mode = fake()->lexify('?');

        $sut = FileStream::fromUrl(fake()->url());
        $sut->setMode($mode);

        expect($sut->getMode())
            ->toBe($mode);
    });

    test('fromUrl() with StreamContext', function (): void {
        $sut = FileStream::fromUrl(fake()->url(), HttpStreamContext::forGet());

        expect($sut->getStreamContext())
            ->toBeInstanceOf(HttpStreamContext::class);
    });
});
