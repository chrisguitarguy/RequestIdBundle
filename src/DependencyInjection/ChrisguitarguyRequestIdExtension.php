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

namespace Chrisguitarguy\RequestId\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Chrisguitarguy\RequestId\SimpleIdStorage;
use Chrisguitarguy\RequestId\RequestIdStorage;
use Chrisguitarguy\RequestId\RequestIdGenerator;
use Chrisguitarguy\RequestId\Generator\RamseyUuid4Generator;
use Chrisguitarguy\RequestId\EventListener\RequestIdListener;
use Chrisguitarguy\RequestId\Monolog\RequestIdProcessor;
use Chrisguitarguy\RequestId\Twig\RequestIdExtension;

/**
 * Registers some container configuration with the application.
 *
 * @since   1.0
 */
final class ChrisguitarguyRequestIdExtension extends ConfigurableExtension
{
    protected function loadInternal(array $config, ContainerBuilder $container): void
    {
        $container->register(SimpleIdStorage::class)
            ->setPublic(false);
        $container->register(RamseyUuid4Generator::class)
            ->setPublic(false);

        $storeId = empty($config['storage_service']) ? SimpleIdStorage::class : $config['storage_service'];
        $genId = empty($config['generator_service']) ? RamseyUuid4Generator::class : $config['generator_service'];

        $container->setAlias(RequestIdStorage::class, $storeId)
            ->setPublic(true);
        $container->setAlias(RequestIdGenerator::class, $genId)
            ->setPublic(true);

        $container->register(RequestIdListener::class)
            ->setArguments([
                $config['request_header'],
                $config['response_header'],
                $config['trust_request_header'],
                new Reference($storeId),
                new Reference($genId),
            ])
            ->setPublic(false)
            ->addTag('kernel.event_subscriber');

        if (!empty($config['enable_monolog'])) {
            $container->register(RequestIdProcessor::class)
                ->addArgument(new Reference($storeId))
                ->setPublic(false)
                ->addTag('monolog.processor');
        }

        if (class_exists('Twig\Extension\AbstractExtension') && !empty($config['enable_twig'])) {
            $container->register(RequestIdExtension::class)
                ->addArgument(new Reference($storeId))
                ->setPublic(false)
                ->addTag('twig.extension');
        }
    }
}
