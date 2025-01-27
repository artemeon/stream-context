<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Context;

use phpseclib3\Net\SFTP\Stream;

/**
 * Object to create sftp://host.com/home/user/filename context based on the phpseclib.
 *
 * @see https://phpseclib.com/docs/sftp#customizing-the-protocol
 */
final class SftpStreamContext extends StreamContext
{
    public const string PROTOCOL = 'sftp';

    private function __construct(
        private readonly string $username,
        private readonly string $password,
        private readonly string $privateKey,
    ) {
        Stream::register(self::PROTOCOL);
    }

    /**
     * Named constructor to create a sftp connection with password authentication.
     *
     * @param string $username Remote username
     * @param string $password Remote password
     */
    public static function forPasswordAuthentication(string $username, string $password): self
    {
        return new self($username, $password, '');
    }

    /**
     * Named constructor to create a sftp connection with private key authentication.
     *
     * @param string $privateKey Private ssh key string
     */
    public static function forPrivateKeyAuthentication(string $privateKey): self
    {
        return new self('', '', $privateKey);
    }

    /**
     * @return array{
     *     sftp: array{
     *         privkey?: non-empty-string,
     *         username?: string,
     *         password?: string,
     *     }
     * }
     */
    protected function getContextOptions(): array
    {
        $context = [
            self::PROTOCOL => [],
        ];

        if ($this->privateKey !== '') {
            $context[self::PROTOCOL]['privkey'] = $this->privateKey;
        } else {
            $context[self::PROTOCOL]['username'] = $this->username;
            $context[self::PROTOCOL]['password'] = $this->password;
        }

        return $context;
    }
}
