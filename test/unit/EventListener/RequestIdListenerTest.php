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

namespace Chrisguitarguy\RequestId\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use Chrisguitarguy\RequestId\RequestIdGenerator;
use Chrisguitarguy\RequestId\RequestIdStorage;
use Chrisguitarguy\RequestId\UnitTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestIdListenerTest extends UnitTestCase
{
    const REQUEST_HEADER = 'Request-Id';
    const RESPONSE_HEADER = 'Response-Id';

    private RequestIdStorage&MockObject $idStorage;
    private RequestIdGenerator&MockObject $idGen;
    private RequestIdListener $listener;
    private EventDispatcher $dispatcher;
    private Request $request;
    private Response $response;
    private HttpKernelInterface&MockObject $kernel;

    public function testNonMasterRequestsDoNothingOnRequest()
    {
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::SUB_REQUEST
        );
        $this->idStorage->expects($this->never())
            ->method('getRequestId');

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    public function testListenerSetsTheRequestIdToStorageWhenFoundInRequestHeaders()
    {
        $this->request->headers->set(self::REQUEST_HEADER, 'testId');
        $this->willNotGenerate();
        $this->idStorage->expects($this->never())
            ->method('getRequestId');
        $this->idStorage->expects($this->once())
            ->method('setRequestId')
            ->with('testId');
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    public function testListenerSetsTheIdOnRequestWhenItsFoundInStorage()
    {
        $this->willNotGenerate();
        $this->idStorage->expects($this->once())
            ->method('getRequestId')
            ->willReturn('abc123');
        $this->idStorage->expects($this->never())
            ->method('setRequestId');
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);

        $this->assertEquals('abc123', $this->request->headers->get(self::REQUEST_HEADER));
    }

    public function testListenerGenerateNewIdAndSetsItOnRequestAndStorageWhenNoIdIsFound()
    {
        $this->idGen->expects($this->once())
            ->method('generate')
            ->willReturn('def234');
        $this->idStorage->expects($this->once())
            ->method('getRequestId')
            ->willReturn(null);
        $this->idStorage->expects($this->once())
            ->method('setRequestId')
            ->with('def234');
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);

        $this->assertEquals('def234', $this->request->headers->get(self::REQUEST_HEADER));
    }

    public function testListenerIgnoresIncomingRequestHeadersWhenTrustRequestIsFalse()
    {
        $this->dispatcher->removeSubscriber($this->listener);
        $this->dispatcher->addSubscriber(
            new RequestIdListener(
                self::REQUEST_HEADER,
                self::REQUEST_HEADER,
                false,
                $this->idStorage,
                $this->idGen
            )
        );
        $this->idGen->expects($this->once())
            ->method('generate')
            ->willReturn('def234');
        $this->idStorage->expects($this->once())
            ->method('getRequestId')
            ->willReturn(null);
        $this->idStorage->expects($this->once())
            ->method('setRequestId')
            ->with('def234');
        $this->request->headers->set(self::REQUEST_HEADER, 'abc123');
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);

        $this->assertEquals('def234', $this->request->headers->get(self::REQUEST_HEADER));
    }

    public function testListenerDoesNothingToResponseWithoutMasterRequest()
    {
        $this->idStorage->expects($this->never())
            ->method('getRequestId');

        $this->dispatcher->dispatch(
            new ResponseEvent(
                $this->kernel,
                $this->request,
                HttpKernelInterface::SUB_REQUEST,
                $this->response
            ),
            KernelEvents::RESPONSE
        );

        $this->assertFalse($this->response->headers->has(self::REQUEST_HEADER));
    }

    public function testRequestWithoutIdInStorageDoesNotSetHeaderOnResponse()
    {
        $this->idStorage->expects($this->once())
            ->method('getRequestId')
            ->willReturn(null);

        $this->dispatcher->dispatch(
            new ResponseEvent(
                $this->kernel,
                $this->request,
                HttpKernelInterface::MAIN_REQUEST,
                $this->response
            ),
            KernelEvents::RESPONSE
        );

        $this->assertFalse($this->response->headers->has(self::REQUEST_HEADER));
    }

    public function testRequestWithIdInStorageSetsIdOnResponse()
    {
        $this->idStorage->expects($this->once())
            ->method('getRequestId')
            ->willReturn('ghi345');

        $this->dispatcher->dispatch(
            new ResponseEvent(
                $this->kernel,
                $this->request,
                HttpKernelInterface::MAIN_REQUEST,
                $this->response
            ),
            KernelEvents::RESPONSE
        );

        $this->assertEquals('ghi345', $this->response->headers->get(self::RESPONSE_HEADER));
    }

    protected function setUp(): void
    {
        $this->idStorage = $this->createMock(RequestIdStorage::class);
        $this->idGen = $this->createMock(RequestIdGenerator::class);
        $this->listener = new RequestIdListener(
            self::REQUEST_HEADER,
            self::RESPONSE_HEADER,
            true,
            $this->idStorage,
            $this->idGen
        );
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($this->listener);
        $this->request = Request::create('/');
        $this->response = new Response('Hello, World');
        $this->kernel = $this->createMock(HttpKernelInterface::class);
    }

    private function willNotGenerate()
    {
        $this->idGen->expects($this->never())
            ->method('generate');
    }
}
