<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Unit\Generator;

use DR\SymfonyRequestId\Generator\SymfonyUuid4Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SymfonyUuid4Generator::class)]
class SymfonyUuid4GeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        // we're not going to mock anything here, I'm more
        // interested in making sure we're using the library
        // correctly than worry about mocking method calls.
        $generator = new SymfonyUuid4Generator();

        $id = $generator->generate();

        static::assertNotEmpty($id);
        static::assertIsString($id);
    }
}
