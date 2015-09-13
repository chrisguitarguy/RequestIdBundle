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

class Uuid4IdGeneratorTest extends UnitTestCase
{
    public function testGenerateReturnsANewStringIdentifier()
    {
        $s = new Uuid4IdGenerator();

        $id = $s->generate();

        $this->assertNotEmpty($id);
        $this->assertInternalType('string', $id);
    }
}
