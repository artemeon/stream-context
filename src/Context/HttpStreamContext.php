<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Context;

/**
 * Object to create http://host.com/home/user/filename context streams.
 */
final class HttpStreamContext extends StreamContext
{
    public const string PROTOCOL = 'http';

    /** @var non-empty-string[] */
    private array $headers = [];
    private ?string $content = null;
    private ?string $userAgent = null;
    private ?float $timeout = null;

    private function __construct(private readonly HttpMethod $method)
    {
    }

    /**
     * Named constructor to create an instance for GET requests.
     */
    public static function forGet(): self
    {
        return new self(HttpMethod::GET);
    }

    /**
     * Named constructor to create an instance for a POST request with the given content string.
     */
    public static function forPost(string $content): self
    {
        $instance = new self(HttpMethod::POST);
        $instance->content = $content;

        return $instance;
    }

    /**
     * Named constructor to create an instance for POST request with url encoded form data.
     *
     * @param array<non-empty-string, string> $parameters
     */
    public static function forPostUrlencoded(array $parameters): self
    {
        $instance = new self(HttpMethod::POST);
        $instance->content = http_build_query($parameters);
        $instance->headers[] = 'Content-type: application/x-www-form-urlencoded';

        return $instance;
    }

    /**
     * Named constructor to create an instance for a PUT request with the given content string.
     */
    public static function forPut(string $content): self
    {
        $instance = new self(HttpMethod::PUT);
        $instance->content = $content;

        return $instance;
    }

    /**
     * Named constructor to create an instance for PUT request with url encoded form data.
     *
     * @param array<non-empty-string, string> $parameters
     */
    public static function forPutUrlencoded(array $parameters): self
    {
        $instance = new self(HttpMethod::PUT);
        $instance->content = http_build_query($parameters);
        $instance->headers[] = 'Content-type: application/x-www-form-urlencoded';

        return $instance;
    }

    /**
     * Add additional headers.
     *
     * @param non-empty-string[] $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = [...$this->headers, ...$headers];
    }

    /**
     * Set a custom user agent.
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * Set a connect timeout in seconds, standard value is 10 seconds.
     */
    public function setTimeout(float $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @return array{
     *     http: array{
     *         method: 'GET' | 'POST' | 'PUT',
     *         timeout: float,
     *         user_agent?: non-empty-string,
     *         header?: non-empty-string[],
     *         content?: string,
     *     }
     * }
     */
    protected function getContextOptions(): array
    {
        $context[self::PROTOCOL]['method'] = $this->method->value;
        $context[self::PROTOCOL]['timeout'] = $this->timeout ?? 10.0;

        if (!empty($this->userAgent)) {
            $context[self::PROTOCOL]['user_agent'] = $this->userAgent;
        }

        if (!empty($this->headers)) {
            $context[self::PROTOCOL]['header'] = $this->headers;
        }

        if (!empty($this->content) || in_array($this->method, [HttpMethod::POST, HttpMethod::PUT], true)) {
            $context[self::PROTOCOL]['content'] = $this->content;
        }

        return $context;
    }
}
