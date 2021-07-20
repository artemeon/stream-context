<?php

declare(strict_types=1);

namespace Artemeon\StreamContext\Context;

/**
 * Object to create http://host.com/home/user/filename context streams
 *
 * @since 0.1
 */
final class HttpStreamContext extends StreamContext
{
    public const PROTOCOL = 'http';

    private string $method = 'GET';
    private array $headers = [];
    private string $content = "";
    private string $userAgent = "";
    private float $timeout = 10.0;

    private function __construct(string $method)
    {
        $this->method = $method;
    }

    /**
     * Named constructor to create an instance for GET requests
     */
    public static function forGet(): self
    {
        return new self('GET');
    }

    /**
     * Named constructor to create an instance for a POST request with the given content string
     */
    public static function forPost(string $content): self
    {
        $instance = new self('POST');
        $instance->content = $content;

        return $instance;
    }

    /**
     * Named constructor to create an instance for POST request with url encoded form data
     */
    public static function forPostUrlencoded(array $parameters): self
    {
        $instance = new self('POST');
        $instance->content = http_build_query($parameters);
        $instance->headers[] = 'Content-type: application/x-www-form-urlencoded';

        return $instance;
    }

    /**
     * Named constructor to create an instance for a PUT request with the given content string
     */
    public static function forPut(string $content): self
    {
        $instance = new self('PUT');
        $instance->content = $content;

        return $instance;
    }

    /**
     * Named constructor to create an instance for PUT request with url encoded form data
     */
    public static function forPutUrlencoded(array $parameters): self
    {
        $instance = new self('PUT');
        $instance->content = http_build_query($parameters);
        $instance->headers[] = 'Content-type: application/x-www-form-urlencoded';

        return $instance;
    }

    /**
     * Add additional headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Set a custom user agent
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * Set a connect timeout in seconds, standard value is 10 seconds
     *
     * @param float $timeout
     */
    public function setTimeout(float $timeout): void
    {
        $this->timeout = $timeout;
    }

    protected function getContextOptions(): array
    {
        $context[self::PROTOCOL]['method'] = $this->method;
        $context[self::PROTOCOL]['timeout'] = $this->timeout;

        if ($this->userAgent !== "") {
            $context[self::PROTOCOL]['user_agent'] = $this->userAgent;
        }

        if (!empty($this->headers)) {
            $context[self::PROTOCOL]['header'] = $this->headers;
        }

        if ($this->content !== "") {
            $context[self::PROTOCOL]['content'] = $this->content;
        }

        return $context;
    }
}