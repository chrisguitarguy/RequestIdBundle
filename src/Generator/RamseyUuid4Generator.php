<?php declare(strict_types=1);

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

use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;
use Chrisguitarguy\RequestId\RequestIdGenerator;

/**
 * Uses version ~3.0 of `ramsey/uuid` to generator v4 UUIDs for request ids.
 *
 * @since   1.0
 */
final class RamseyUuid4Generator implements RequestIdGenerator
{
    /**
     * @var UuidFactoryInterface
     */
    private $factory;

    public function __construct(UuidFactoryInterface $factory = null)
    {
        $this->factory = $factory ?: new UuidFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function generate(): string
    {
        return (string) $this->factory->uuid4();
    }
}
