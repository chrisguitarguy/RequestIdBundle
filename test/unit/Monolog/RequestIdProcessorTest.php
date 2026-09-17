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

use PHPUnit\Framework\MockObject\MockObject;
use Chrisguitarguy\RequestId\RequestIdStorage;
use Chrisguitarguy\RequestId\UnitTestCase;
use Monolog\Level;
use Monolog\Logger;
use Monolog\LogRecord;

class RequestIdProcessorTest extends UnitTestCase
{
    private RequestIdStorage&MockObject $idStorage;
    private RequestIdProcessor $processor;


    public function testProcessorDoesNotSetRequestIdWhenNoIdIsPresentWithMonologAtLeast3() : void
    {
        $this->withRequestId(null);
        $record = call_user_func(
            $this->processor,
            new LogRecord(new \DateTimeImmutable('now'), 'channel', Level::Info, 'foo')
        );

        $this->assertArrayNotHasKey('request_id', $record->extra);
    }

    public function testProcessorAddsRequestIdWhenIdIsPresentWithMonologAtLeast3() : void
    {
        $this->withRequestId('abc123');
        $record = call_user_func(
            $this->processor,
            new LogRecord(new \DateTimeImmutable('now'), 'channel', Level::Info, 'foo')
        );

        $this->assertArrayHasKey('request_id', $record->extra);
        $this->assertEquals('abc123', $record->extra['request_id']);
    }

    protected function setUp(): void
    {
        $this->idStorage = $this->createMock(RequestIdStorage::class);
        $this->processor = new RequestIdProcessor($this->idStorage);
    }

    private function withRequestId($id) : void
    {
        $this->idStorage->expects($this->once())
            ->method('getRequestId')
            ->willReturn($id);
    }
}
