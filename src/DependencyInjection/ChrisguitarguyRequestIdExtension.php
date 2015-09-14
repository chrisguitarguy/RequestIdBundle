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
use Chrisguitarguy\RequestId\Monolog\RequestIdProcessor;
use Chrisguitarguy\RequestId\Twig\RequestIdExtension;

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
            $config['trust_request_header'],
            new Reference($storeId),
            new Reference($genId),
        ]);
        $listenerDef->addTag('kernel.event_subscriber');
        $container->setDefinition('chrisguitarguy.requestid.listener', $listenerDef);

        if (!empty($config['enable_monolog'])) {
            $logDef = new Definition(RequestIdProcessor::class, [
                new Reference($storeId),
            ]);
            $logDef->addTag('monolog.processor');
            $container->setDefinition('chrisguitarguy.requestid.monolog_processor', $logDef);
        }

        if (class_exists('Twig_Extension') && !empty($config['enable_twig'])) {
            $twigDef = new Definition(RequestIdExtension::class, [
                new Reference($storeId),
            ]);
            $twigDef->addTag('twig.extension');
            $container->setDefinition('chrisguitarguy.requestid.twig_extension', $twigDef);
        }
    }
}
