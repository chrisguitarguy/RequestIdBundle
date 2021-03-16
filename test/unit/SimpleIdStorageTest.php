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

namespace Chrisguitarguy\RequestId;

class SimpleIdStorageTest extends UnitTestCase
{
    public function testGetRequestIdReturnsTheSameValueThatWasSet()
    {
        $s = new SimpleIdStorage();

        $this->assertNull($s->getRequestId());
        $s->setRequestId('test');
        $this->assertEquals('test', $s->getRequestId());
    }

    public function testNullCanBePassedToSetRequestIdToClearIt()
    {
        $s = new SimpleIdStorage();
        $s->setRequestId('test');

        $s->setRequestId(null);

        $this->assertNull($s->getRequestId());
    }
}
