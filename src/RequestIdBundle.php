<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId;

use DR\SymfonyRequestId\DependencyInjection\SymfonyRequestIdExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RequestIdBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function getContainerExtension(): ExtensionInterface
    {
        return new SymfonyRequestIdExtension();
    }
}
