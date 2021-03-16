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

use Ramsey\Uuid\UuidFactoryInterface;
use Chrisguitarguy\RequestId\UnitTestCase;

class RamseyUuid4GeneratorTest extends UnitTestCase
{
    public function testGenerateReturnsANewStringIdentifier()
    {
        if (!interface_exists(UuidFactoryInterface::class)) {
            return $this->markTestSkipped(sprintf('%s requires the %s class', __METHOD__, UuidFactoryInterface::class));
        }

        // we're not going to mock anything here, I'm more
        // interested in making sure we're using the library
        // correctly than worry about mocking method calls.
        $s = new RamseyUuid4Generator();

        $id = $s->generate();

        $this->assertNotEmpty($id);
        $this->assertIsString($id);
    }
}
