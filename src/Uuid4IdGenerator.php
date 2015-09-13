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

namespace Chrisguitarguy\RequestId;

use Rhumsaa\Uuid\Uuid;

/**
 * Use v4 UUID's for request IDs.
 *
 * @since   1.0
 */
final class Uuid4IdGenerator implements RequestIdGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return (string) Uuid::uuid4();
    }
}
