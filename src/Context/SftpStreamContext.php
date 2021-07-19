<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Context;

use phpseclib3\Net\SFTP\Stream;

/**
 * Factory to create sftp://host.com/home/user/filename context based on the
 *
 * @see https://phpseclib.com/docs/sftp#customizing-the-protocol
 *
 * @since 8.0
 */
final class SftpStreamContext extends StreamContext
{
    private const PROTOCOL = 'sftp';

    private string $username;
    private string $password;
    private string $privateKey;

    private function __construct(string $username, string $password, string $privateKey)
    {
        $this->username = $username;
        $this->password = $password;
        $this->privateKey = $privateKey;

        Stream::register(self::PROTOCOL);
    }

    /**
     * Named constructor to create a sftp connection with password authentication
     *
     * @param string $username Remote username
     * @param string $password Remote password
     */
    public static function forPasswordAuthentication(string $username, string $password): self
    {
        return new self($username, $password, "");
    }

    /**
     * Named constructor to create a sftp connection with private key authentication
     *
     * @param string $privateKey Private ssh key string
     */
    public static function forPrivateKeyAuthentication(string $privateKey): self
    {
        return new self("", "", $privateKey);
    }

    protected function getContextOptions(): array
    {
        if ($this->privateKey !== "") {
            $context[self::PROTOCOL] = [
                'privkey' => $this->privateKey,
            ];
        } else {
            $context[self::PROTOCOL] = [
                'username' => $this->username,
                'password' => $this->password,
            ];
        }

        return $context;
    }
}
