<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Unit\EventListener;

use DR\SymfonyRequestId\EventListener\RequestIdListener;
use DR\SymfonyRequestId\RequestIdGenerator;
use DR\SymfonyRequestId\RequestIdStorage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

#[CoversClass(RequestIdListener::class)]
class RequestIdListenerTest extends TestCase
{
    private const REQUEST_HEADER  = 'Request-Id';
    private const RESPONSE_HEADER = 'Response-Id';

    private RequestIdStorage&MockOBject $idStorage;
    private RequestIdGenerator&MockObject $idGen;
    private RequestIdListener $listener;
    private EventDispatcher $dispatcher;
    private Request $request;
    private Response $response;
    private HttpKernelInterface&MockObject $kernel;

    protected function setUp(): void
    {
        $this->idStorage  = $this->createMock(RequestIdStorage::class);
        $this->idGen      = $this->createMock(RequestIdGenerator::class);
        $this->listener   = new RequestIdListener(
            self::REQUEST_HEADER,
            self::RESPONSE_HEADER,
            true,
            $this->idStorage,
            $this->idGen
        );
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($this->listener);
        $this->request  = Request::create('/');
        $this->response = new Response('Hello, World');
        $this->kernel   = $this->createMock(HttpKernelInterface::class);
    }

    public function testNonMasterRequestsDoNothingOnRequest(): void
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

    public function testListenerSetsTheRequestIdToStorageWhenFoundInRequestHeaders(): void
    {
        $this->request->headers->set(self::REQUEST_HEADER, 'testId');
        $this->willNotGenerate();
        $this->idStorage->expects($this->never())
            ->method('getRequestId');
        $this->idStorage->expects(self::once())
            ->method('setRequestId')
            ->with('testId');
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);
    }

    public function testListenerSetsTheIdOnRequestWhenItsFoundInStorage(): void
    {
        $this->willNotGenerate();
        $this->idStorage->expects(self::once())
            ->method('getRequestId')
            ->willReturn('abc123');
        $this->idStorage->expects(self::never())
            ->method('setRequestId');
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);

        static::assertEquals('abc123', $this->request->headers->get(self::REQUEST_HEADER));
    }

    public function testListenerGenerateNewIdAndSetsItOnRequestAndStorageWhenNoIdIsFound(): void
    {
        $this->idGen->expects(self::once())
            ->method('generate')
            ->willReturn('def234');
        $this->idStorage->expects(self::once())
            ->method('getRequestId')
            ->willReturn(null);
        $this->idStorage->expects(self::once())
            ->method('setRequestId')
            ->with('def234');
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);

        static::assertEquals('def234', $this->request->headers->get(self::REQUEST_HEADER));
    }

    public function testListenerIgnoresIncomingRequestHeadersWhenTrustRequestIsFalse(): void
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
        $this->idGen->expects(self::once())
            ->method('generate')
            ->willReturn('def234');
        $this->idStorage->expects(self::once())
            ->method('getRequestId')
            ->willReturn(null);
        $this->idStorage->expects(self::once())
            ->method('setRequestId')
            ->with('def234');
        $this->request->headers->set(self::REQUEST_HEADER, 'abc123');
        $event = new RequestEvent(
            $this->kernel,
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->dispatcher->dispatch($event, KernelEvents::REQUEST);

        static::assertEquals('def234', $this->request->headers->get(self::REQUEST_HEADER));
    }

    public function testListenerDoesNothingToResponseWithoutMasterRequest(): void
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

        static::assertFalse($this->response->headers->has(self::REQUEST_HEADER));
    }

    public function testRequestWithoutIdInStorageDoesNotSetHeaderOnResponse(): void
    {
        $this->idStorage->expects(self::once())
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

        static::assertFalse($this->response->headers->has(self::REQUEST_HEADER));
    }

    public function testRequestWithIdInStorageSetsIdOnResponse(): void
    {
        $this->idStorage->expects(self::once())
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

        static::assertEquals('ghi345', $this->response->headers->get(self::RESPONSE_HEADER));
    }

    private function willNotGenerate(): void
    {
        $this->idGen->expects($this->never())->method('generate');
    }
}
