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

namespace Chrisguitarguy\RequestId\Monolog;

use Chrisguitarguy\RequestId\RequestIdStorage;
use Chrisguitarguy\RequestId\UnitTestCase;

class RequestIdProcessorTest extends UnitTestCase
{
    private $idStorage, $processor;

    public function testProcessorDoesNotSetRequestIdWhenNoIdIsPresent()
    {
        $this->withRequestId(null);

        $record = call_user_func($this->processor, ['extra' => []]);

        $this->assertArrayNotHasKey('request_id', $record['extra']);
    }

    public function testProcessorAddsRequestIdWhenIdIsPresent()
    {
        $this->withRequestId('abc123');

        $record = call_user_func($this->processor, ['extra' => []]);

        $this->assertArrayHasKey('request_id', $record['extra']);
        $this->assertEquals('abc123', $record['extra']['request_id']);
    }

    protected function setUp()
    {
        $this->idStorage = $this->createMock(RequestIdStorage::class);
        $this->processor = new RequestIdProcessor($this->idStorage);
    }

    private function withRequestId($id)
    {
        $this->idStorage->expects($this->once())
            ->method('getRequestId')
            ->willReturn($id);
    }
}
