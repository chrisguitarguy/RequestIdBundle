<?php
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

use Rhumsaa\Uuid\Uuid;
use Chrisguitarguy\RequestId\RequestIdGenerator;

/**
 * Uses version ~2.0 of `ramsey/uuid` to generator v4 UUIDs for request ids.
 *
 * @since   1.0
 */
final class RhumsaaUuid4Generator implements RequestIdGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return (string) Uuid::uuid4();
    }
}
