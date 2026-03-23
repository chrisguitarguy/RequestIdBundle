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

use Ramsey\Uuid\UuidFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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
 * Registers some container congiruation with the application.
 *
 * @since   1.0
 */
final class ChrisguitarguyRequestIdExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container) : void
    {
        $container->register(SimpleIdStorage::class)
            ->setPublic(false);
        $container->register(RamseyUuid4Generator::class)
            ->setPublic(false);

        $storeId = empty($mergedConfig['storage_service']) ? SimpleIdStorage::class : $mergedConfig['storage_service'];
        $genId = empty($mergedConfig['generator_service']) ? RamseyUuid4Generator::class : $mergedConfig['generator_service'];

        $container->setAlias(RequestIdStorage::class, $storeId)
            ->setPublic(true);
        $container->setAlias(RequestIdGenerator::class, $genId)
            ->setPublic(true);

        $container->register(RequestIdListener::class)
            ->setArguments([
                $mergedConfig['request_header'],
                $mergedConfig['response_header'],
                $mergedConfig['trust_request_header'],
                new Reference($storeId),
                new Reference($genId),
            ])
            ->setPublic(false)
            ->addTag('kernel.event_subscriber');

        if (!empty($mergedConfig['enable_monolog'])) {
            $container->register(RequestIdProcessor::class)
                ->addArgument(new Reference($storeId))
                ->setPublic(false)
                ->addTag('monolog.processor');
        }

        if (class_exists('Twig\Extension\AbstractExtension') && !empty($mergedConfig['enable_twig'])) {
            $container->register(RequestIdExtension::class)
                ->addArgument(new Reference($storeId))
                ->setPublic(false)
                ->addTag('twig.extension');
        }
    }
}
