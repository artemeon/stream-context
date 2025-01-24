<?php

use Artemeon\StreamContext\Context\SftpStreamContext;

use function Pest\Faker\fake;

describe('HttpStreamContext', function (): void {
    test('forPasswordAuthentication()', function (): void {
        $username = fake()->userName();
        $password = fake()->password();

        $context = SftpStreamContext::forPasswordAuthentication($username, $password);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        expect($result)
            ->toHaveKey('sftp')
            ->and($result['sftp'])
            ->toHaveKey('username')
            ->and($result['sftp']['username'])
            ->toBe($username)
            ->and($result['sftp'])
            ->toHaveKey('password')
            ->and($result['sftp']['password'])
            ->toBe($password);
    });

    test('forPrivateKeyAuthentication()', function (): void {
        $privateKey = fake()->password();

        $context = SftpStreamContext::forPrivateKeyAuthentication($privateKey);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        expect($result)
            ->toHaveKey('sftp')
            ->and($result['sftp'])
            ->toHaveKey('privkey')
            ->and($result['sftp']['privkey'])
            ->toBe($privateKey);
    });
});
