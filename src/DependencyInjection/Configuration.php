<?php

declare(strict_types=1);

namespace DR\SymfonyRequestId\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @internal
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree = new TreeBuilder('symfony_request_id');

        $tree->getRootNode()
            ->children()
            ->scalarNode('request_header')
                ->cannotBeEmpty()
                ->defaultValue('X-Request-Id')
                ->info('The header in which the bundle will look for and set request IDs')
            ->end()
            ->booleanNode('trust_request_header')
                ->defaultValue(true)
                ->info("Whether or not to trust the incoming request's `Request-Id` header as a real ID")
            ->end()
            ->scalarNode('response_header')
                ->cannotBeEmpty()
                ->defaultValue('X-Request-Id')
                ->info('The header the bundle will set the request ID at in the response')
            ->end()
            ->scalarNode('storage_service')
                ->info('The service name for request ID storage. Defaults to `SimpleIdStorage`')
            ->end()
            ->scalarNode('generator_service')
                ->info('The service name for the request ID generator. Defaults to `symfony/uid` or `ramsey/uuid`')
            ->end()
            ->booleanNode('enable_monolog')
                ->info('Whether or not to turn on the request ID processor for monolog')
                ->defaultTrue()
            ->end()
            ->booleanNode('enable_console')
                ->info('Whether or not to turn on the request ID processor for monolog')
                ->defaultTrue()
            ->end()
            ->booleanNode('enable_twig')
                ->info('Whether or not to enable the twig `request_id()` function. Only works if TwigBundle is present.')
                ->defaultTrue()
            ->end();

        return $tree;
    }
}
