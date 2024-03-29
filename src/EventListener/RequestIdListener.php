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

namespace Chrisguitarguy\RequestId\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Chrisguitarguy\RequestId\RequestIdGenerator;
use Chrisguitarguy\RequestId\RequestIdStorage;

/**
 * Listens for requests and responses and sets up the request ID on each.
 *
 * @since   1.0
 */
final class RequestIdListener implements EventSubscriberInterface
{
    /**
     * The header to inspect for the incoming request ID.
     */
    private string $requestHeader;

    /**
     * The header that will contain the request ID in the response.
     */
    private string $responseHeader;

    /**
     * Trust the value from the request? Or generate?
     */
    private bool $trustRequest;

    /**
     * The request ID storage, used to store the ID from the request or a
     * newly generated ID.
     */
    private RequestIdStorage $idStorage;

    /**
     * Used to generate a request ID if one isn't present.
     */
    private RequestIdGenerator $idGenerator;

    /**
     * symfony5-compat
     *
     * Stores whether we have an `isMainRequest` method to use instead of isMasterRequest
     */
    private bool $hasIsMainRequest;

    public function __construct(string $reqHeader, string $respHeader, bool $trustReq, RequestIdStorage $storage, RequestIdGenerator $generator)
    {
        $this->requestHeader = $reqHeader;
        $this->responseHeader = $respHeader;
        $this->trustRequest = $trustReq;
        $this->idStorage = $storage;
        $this->idGenerator = $generator;
        $this->hasIsMainRequest = method_exists(KernelEvent::class, 'isMainRequest');
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::REQUEST   => ['onRequest', 100],
            KernelEvents::RESPONSE  => ['onResponse', -99],
        ];
    }

    public function onRequest(RequestEvent $event) : void
    {
        if (!$this->isMainRequest($event)) {
            return;
        }

        $req = $event->getRequest();

        // always give the incoming request priority. If it has the ID in
        // its headers already put that into our ID storage.
        if ($this->trustRequest && ($id = $req->headers->get($this->requestHeader))) {
            $this->idStorage->setRequestId($id);
            return;
        }

        // similarly, if the request ID storage already has an ID set we
        // don't need to do anything other than put it into the request headers
        if ($id = $this->idStorage->getRequestId()) {
            $req->headers->set($this->requestHeader, $id);
            return;
        }

        $id = $this->idGenerator->generate();
        $req->headers->set($this->requestHeader, $id);
        $this->idStorage->setRequestId($id);
    }

    public function onResponse(ResponseEvent $event) : void
    {
        if (!$this->isMainRequest($event)) {
            return;
        }

        if ($id = $this->idStorage->getRequestId()) {
            $event->getResponse()->headers->set($this->responseHeader, $id);
        }
    }

    private function isMainRequest(KernelEvent $event) : bool
    {
        return $this->hasIsMainRequest ? $event->isMainRequest() : $event->isMasterRequest();
    }
}
