<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Unit\Twig;

use DR\SymfonyRequestId\SimpleIdStorage;
use DR\SymfonyRequestId\Twig\RequestIdExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Throwable;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

#[CoversClass(RequestIdExtension::class)]
class RequestIdExtensionTest extends TestCase
{
    private const TEMPLATE = '{{ request_id() }}';

    private Environment $environment;
    private SimpleIdStorage $storage;

    protected function setUp(): void
    {
        $this->environment = new Environment(new ArrayLoader(['test' => self::TEMPLATE]));
        $this->environment->addExtension(new RequestIdExtension($this->storage));
        $this->storage = new SimpleIdStorage();
    }

    /**
     * @throws Throwable
     */
    public function testTwigRequestIdFunction(): void
    {
        $this->storage->setRequestId('abc123');

        $result = $this->environment->render('test');

        static::assertSame($result, 'abc123');
    }
}
