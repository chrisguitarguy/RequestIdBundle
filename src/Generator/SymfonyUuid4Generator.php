<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Generator;

use DR\SymfonyRequestId\RequestIdGenerator;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\UuidV4;

/**
 * Uses symfony/uid to generate a UUIDv4 request ID.
 */
final class SymfonyUuid4Generator implements RequestIdGenerator
{
    public function __construct(private readonly UuidFactory $factory = new UuidFactory(UuidV4::class, UuidV4::class, UuidV4::class, UuidV4::class))
    {
    }

    public static function isSupported(): bool
    {
        return class_exists(UuidFactory::class);
    }

    public function generate(): string
    {
        return (string)$this->factory->create();
    }
}
