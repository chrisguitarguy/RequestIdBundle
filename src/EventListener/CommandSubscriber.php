<?php

namespace Chrisguitarguy\RequestId\EventListener;

use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Chrisguitarguy\RequestId\RequestIdGenerator;
use Chrisguitarguy\RequestId\RequestIdStorage;

/**
 * Set up request id for command.
 *
 * @since   1.0
 */
final class CommandSubscriber implements EventSubscriberInterface
{
    private RequestIdStorage $requestIdStorage;
    private RequestIdGenerator $generator;

    public function __construct(
        RequestIdStorage $requestIdStorage,
        RequestIdGenerator $generator
    )
    {
        $this->requestIdStorage = $requestIdStorage;
        $this->generator = $generator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConsoleEvents::COMMAND => [
                ['onCommand', 999],
            ],
            ConsoleEvents::TERMINATE => [
                ['onTerminate', -999],
            ],
        ];
    }

    public function onCommand(): void
    {
        $this->requestIdStorage->setRequestId("CLI{$this->generator->generate()}");
    }

    public function onTerminate(): void
    {
        $this->requestIdStorage->setRequestId(null);
    }
}
