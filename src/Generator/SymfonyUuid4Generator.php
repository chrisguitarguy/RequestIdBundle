<?php
declare(strict_types=1);

/*
 * This file is part of chrisguitarguy/request-id-bundle

 * Copyright (c) Christopher Davis <http://christopherdavis.me>
 *
 * For full copyright information see the LICENSE file distributed
 * with this source code.
 *
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace Chrisguitarguy\RequestId\Generator;

use Chrisguitarguy\RequestId\RequestIdGenerator;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\UuidV4;

/**
 * Uses symfony/uid to generate a UUIDv4 request ID.
 */
final class SymfonyUuid4Generator implements RequestIdGenerator
{
    /**
     * @var UuidFactory
     */
    private $factory;

    public function __construct(UuidFactory $factory = null)
    {
        $this->factory = $factory ?: new UuidFactory(UuidV4::class, UuidV4::class, UuidV4::class, UuidV4::class);
    }

    /**
     * {@inheritdoc}
     */
    public function generate(): string
    {
        return (string)$this->factory->create();
    }
}
