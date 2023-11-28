<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\Twig;

use DR\SymfonyRequestId\RequestIdStorage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Add request_id() to twig as a function.
 * @internal
 */
final class RequestIdExtension extends AbstractExtension
{
    public function __construct(private readonly RequestIdStorage $storage)
    {
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [new TwigFunction('request_id', [$this->storage, 'getRequestId'])];
    }
}
