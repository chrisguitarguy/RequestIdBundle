<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Unit;

use DR\SymfonyRequestId\SimpleIdStorage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SimpleIdStorage::class)]
class SimpleIdStorageTest extends TestCase
{
    public function testGetRequestIdReturnsTheSameValueThatWasSet(): void
    {
        $storage = new SimpleIdStorage();

        static::assertNull($storage->getRequestId());
        $storage->setRequestId('test');
        static::assertSame('test', $storage->getRequestId());
    }

    public function testNullCanBePassedToSetRequestIdToClearIt(): void
    {
        $storage = new SimpleIdStorage();
        $storage->setRequestId('test');

        $storage->setRequestId(null);

        static::assertNull($storage->getRequestId());
    }
}
