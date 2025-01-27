<?php

use Artemeon\StreamContext\Context\HttpStreamContext;

use function Pest\Faker\fake;

covers(HttpStreamContext::class);
describe('HttpStreamContext', function (): void {
    test('forGet()', function (): void {
        $context = HttpStreamContext::forGet();

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $result = $reflection->invoke($context);

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('GET')
            ->and($result['http'])
            ->not->toHaveKey('content')
            ->and($result['http']['timeout'])
            ->toBe(10.0);
    });

    test('forPost()', function (mixed $content): void {
        $context = HttpStreamContext::forPost($content);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $result = $reflection->invoke($context);

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('POST')
            ->and($result['http']['content'])
            ->toBe($content);
    })->with([
        'content' => [fake()->text()],
        'no-content' => [''],
    ]);

    test('forPostUrlencoded()', function (): void {
        $content = [
            'foo' => fake()->text(),
        ];

        $context = HttpStreamContext::forPostUrlencoded($content);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $result = $reflection->invoke($context);

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('POST')
            ->and($result['http']['header'])
            ->toContain('Content-type: application/x-www-form-urlencoded')
            ->and($result['http']['content'])
            ->toBe(http_build_query($content));
    });

    test('forPut()', function (mixed $content): void {
        $context = HttpStreamContext::forPut($content);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $result = $reflection->invoke($context);

        expect($result)
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('PUT')
            ->and($result['http']['content'])
            ->toBe($content);
    })->with([
        'content' => [fake()->text()],
        'no-content' => [''],
    ]);

    test('forPutUrlencoded()', function (): void {
        $content = [
            'foo' => fake()->text(),
        ];

        $context = HttpStreamContext::forPutUrlencoded($content);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $result = $reflection->invoke($context);

        expect($result)
            ->toBeArray()
            ->toHaveKey('http')
            ->and($result['http'])
            ->toHaveKey('method')
            ->and($result['http']['method'])
            ->toBe('PUT')
            ->and($result['http']['header'])
            ->toContain('Content-type: application/x-www-form-urlencoded')
            ->and($result['http']['content'])
            ->toBe(http_build_query($content));
    });

    test('setHeaders()', function (): void {
        $header1 = 'X-' . fake()->word() . ': ' . fake()->word();
        $header2 = 'X-' . fake()->word() . ': ' . fake()->word();

        $context = HttpStreamContext::forGet();
        $context->setHeaders([$header1]);

        $reflection = new ReflectionMethod($context, 'getContextOptions');
        $result1 = $reflection->invoke($context);

        $context->setHeaders([$header2]);

        $result2 = $reflection->invoke($context);

        expect($result1['http']['header'])
            ->toContain($header1)
            ->and($result2['http']['header'])
            ->toContain($header1)
            ->and($result2['http']['header'])
            ->toContain($header2);
    });

    test('setUserAgent()', function (): void {
        $userAgent = fake()->userAgent();

        $context = HttpStreamContext::forGet();

        $reflection = new ReflectionMethod($context, 'getContextOptions');

        $result = $reflection->invoke($context);

        expect($result['http'])
            ->and($result['http'])
            ->not->toHaveKey('user_agent');

        $context->setUserAgent($userAgent);

        $newResult = $reflection->invoke($context);

        expect($newResult['http'])
            ->toHaveKey('user_agent')
            ->and($newResult['http']['user_agent'])
            ->toBe($userAgent);
    });

    test('setTimeout()', function (): void {
        $timeout = fake()->randomFloat();

        $context = HttpStreamContext::forGet();
        $context->setTimeout($timeout);

        $reflection = new ReflectionMethod($context, 'getContextOptions');

        $result = $reflection->invoke($context);

        expect($result['http'])
            ->toHaveKey('timeout')
            ->and($result['http']['timeout'])
            ->toBe($timeout);
    });

    test('createStreamContext()', function (): void {
        expect(HttpStreamContext::forGet()->createStreamContext())
            ->toBeResource();
    });
});
