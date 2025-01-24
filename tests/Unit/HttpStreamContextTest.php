<?php

use Artemeon\StreamContext\Context\HttpStreamContext;

use function Pest\Faker\fake;

describe('HttpStreamContext', function (): void {
    test('forGet()', function (): void {
        $context = HttpStreamContext::forGet();

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        $stream = $context->createStreamContext();

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('GET')
            ->and($stream)
            ->toBeResource();
    });

    test('forPost()', function (): void {
        $content = fake()->text();

        $context = HttpStreamContext::forPost($content);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        $stream = $context->createStreamContext();

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('POST')
            ->and($result['http']['content'])
            ->toBe($content)
            ->and($stream)
            ->toBeResource();
    });

    test('forPostUrlencoded()', function (): void {
        $content = [
            'foo' => fake()->text(),
        ];

        $context = HttpStreamContext::forPostUrlencoded($content);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        $stream = $context->createStreamContext();

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('POST')
            ->and($result['http']['header'])
            ->toContain('Content-type: application/x-www-form-urlencoded')
            ->and($result['http']['content'])
            ->toBe(http_build_query($content))
            ->and($stream)
            ->toBeResource();
    });

    test('forPut()', function (): void {
        $content = fake()->text();

        $context = HttpStreamContext::forPut($content);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        $stream = $context->createStreamContext();

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('PUT')
            ->and($result['http']['content'])
            ->toBe($content)
            ->and($stream)
            ->toBeResource();
    });

    test('forPutUrlencoded()', function (): void {
        $content = [
            'foo' => fake()->text(),
        ];

        $context = HttpStreamContext::forPutUrlencoded($content);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        $stream = $context->createStreamContext();

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('PUT')
            ->and($result['http']['header'])
            ->toContain('Content-type: application/x-www-form-urlencoded')
            ->and($result['http']['content'])
            ->toBe(http_build_query($content))
            ->and($stream)
            ->toBeResource();
    });

    test('setHeaders()', function (): void {
        $header = 'X-' . fake()->word() . ': ' . fake()->word();

        $context = HttpStreamContext::forGet();
        $context->setHeaders([$header]);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        $stream = $context->createStreamContext();

        expect($result['http']['header'])
            ->toContain($header)
            ->and($stream)
            ->toBeResource();
    });

    test('setUserAgent()', function (): void {
        $userAgent = fake()->userAgent();

        $context = HttpStreamContext::forGet();
        $context->setUserAgent($userAgent);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        $stream = $context->createStreamContext();

        expect($result['http'])
            ->toHaveKey('user_agent')
            ->and($result['http']['user_agent'])
            ->toBe($userAgent)
            ->and($stream)
            ->toBeResource();
    });

    test('setTimeout()', function (): void {
        $timeout = fake()->randomFloat(min: 11.0);

        $context = HttpStreamContext::forGet();
        $context->setTimeout($timeout);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($context);

        $stream = $context->createStreamContext();

        expect($result['http'])
            ->toHaveKey('timeout')
            ->and($result['http']['timeout'])
            ->toBe($timeout)
            ->and($stream)
            ->toBeResource();
    });
});
