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
use Chrisguitarguy\RequestId\UnitTestCase;

class RhumsaaUuid4GeneratorTest extends UnitTestCase
{
    public function testGenerateReturnsANewStringIdentifier()
    {
        if (!class_exists(Uuid::class)) {
            return $this->markTestSkipped(sprintf('%s requires the %s class', __METHOD__, Uuid::class));
        }

        $s = new RhumsaaUuid4Generator();

        $id = $s->generate();

        $this->assertNotEmpty($id);
        $this->assertInternalType('string', $id);
    }
}
