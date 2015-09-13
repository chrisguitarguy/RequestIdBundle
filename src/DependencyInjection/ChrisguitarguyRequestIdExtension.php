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

namespace Chrisguitarguy\RequestId\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Chrisguitarguy\RequestId\SimpleIdStorage;
use Chrisguitarguy\RequestId\Uuid4IdGenerator;
use Chrisguitarguy\RequestId\EventListener\RequestIdListener;

/**
 * Registers some container congiruation with the application.
 *
 * @since   1.0
 */
final class ChrisguitarguyRequestIdExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $container->setDefinition('chrisguitarguy.requestid.storage', new Definition(SimpleIdStorage::class));
        $container->setDefinition('chrisguitarguy.requestid.generator', new Definition(Uuid4IdGenerator::class));

        $storeId = empty($config['storage_service']) ? 'chrisguitarguy.requestid.storage' : $config['storage_service'];
        $genId = empty($config['generator_service']) ? 'chrisguitarguy.requestid.generator' : $config['generator_service'];

        $listenerDef = new Definition(RequestIdListener::class, [
            $config['request_header'],
            $config['response_header'],
            new Reference($storeId),
            new Reference($genId),
        ]);
        $listenerDef->addTag('kernel.event_subscriber');
        $container->setDefinition('chrisguitarguy.requestid.listener', $listenerDef);
    }
}
