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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $tree = new TreeBuilder();
        $root = $tree->root('chrisguitarguy_request_id');

        $root
            ->children()
            ->scalarNode('request_header')
                ->cannotBeEmpty()
                ->defaultValue('Request-Id')
                ->info('The header in which the bundle will look for and set request IDs')
            ->end()
            ->booleanNode('trust_request_header')
                ->defaultValue(true)
                ->info("Whether or not to trust the incoming request's `Request-Id` header as a real ID")
            ->end()
            ->scalarNode('response_header')
                ->cannotBeEmpty()
                ->defaultValue('Request-Id')
                ->info('The header the bundle will set the request ID at in the response')
            ->end()
            ->scalarNode('storage_service')
                ->info('The service name for request ID storage. Defaults to `SimpleIdStorage`')
            ->end()
            ->scalarNode('generator_service')
                ->info('The service name for the request ID generator. Defaults to `Uuid4IdGenerator`')
            ->end()
            ->booleanNode('enable_monolog')
                ->info('Whether or not to turn on the request ID processor for monolog')
                ->defaultTrue()
            ->end()
            ->booleanNode('enable_twig')
                ->info('Whether or not to enable the twig `request_id()` function. Only works if TwigBundle is present.')
                ->defaultTrue()
            ->end()
        ;

        return $tree;
    }
}
