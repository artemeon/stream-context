<?php

use Artemeon\StreamContext\Context\SftpStreamContext;

use function Pest\Faker\fake;

covers(SftpStreamContext::class);
describe('SftpStreamContext', function (): void {
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

        $optionsReflection = new ReflectionMethod($context, 'getContextOptions');
        $optionsReflection->setAccessible(true);

        $usernameReflection = new ReflectionProperty($context, 'username');
        $username = $usernameReflection->getValue($context);

        $passwordReflection = new ReflectionProperty($context, 'password');
        $password = $passwordReflection->getValue($context);

        $options = $optionsReflection->invoke($context);

        expect($options)
            ->toHaveKey('sftp')
            ->and($options['sftp'])
            ->toHaveKey('privkey')
            ->and($options['sftp']['privkey'])
            ->toBe($privateKey)
            ->and($username)
            ->toBe('')
            ->and($password)
            ->toBe('');
    });

    test('isRegistered()', function (): void {
        SftpStreamContext::forPrivateKeyAuthentication('foo');

        expect(stream_get_wrappers())
            ->toContain('sftp');
    });
});
