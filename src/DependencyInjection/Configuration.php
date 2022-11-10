<?php

namespace Zenstruck\BackupBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;

/**
 * @author Kevin Bond <kbond@inboxmarketer.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @param Factory[] $namerFactories
     * @param Factory[] $processorFactories
     * @param Factory[] $sourceFactories
     * @param Factory[] $destinationFactories
     */
    public function __construct(
        private array $namerFactories,
        private array $processorFactories,
        private array $sourceFactories,
        private array $destinationFactories
    ) {
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('zenstruck_backup');
        $rootNode = $treeBuilder->getRootNode();

        $this->addFactories($rootNode, 'namers', 'namer', $this->namerFactories);
        $this->addFactories($rootNode, 'processors', 'processor', $this->processorFactories);
        $this->addFactories($rootNode, 'sources', 'source', $this->sourceFactories);
        $this->addFactories($rootNode, 'destinations', 'destination', $this->destinationFactories);

        $rootNode
            ->children()
                ->arrayNode('profiles')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('scratch_dir')->defaultValue('%kernel.cache_dir%/backup')->end()
                            ->arrayNode('sources')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')->end()
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(fn($v) => [$v])
                                ->end()
                            ->end()
                            ->scalarNode('namer')->isRequired()->end()
                            ->scalarNode('processor')->isRequired()->end()
                            ->arrayNode('destinations')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->prototype('scalar')->end()
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(fn($v) => [$v])
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    public function getSourceFactory(string $id): Factory
    {
        return $this->sourceFactories[$id];
    }

    public function getNamerFactory(string $id): Factory
    {
        return $this->namerFactories[$id];
    }

    public function getDestinationFactory(string $id): Factory
    {
        return $this->destinationFactories[$id];
    }

    public function getProcessorFactory(string $id): Factory
    {
        return $this->processorFactories[$id];
    }

    /**
     * @param Factory[] $factories
     */
    private function addFactories(ArrayNodeDefinition $node, string $plural, string $singular, array $factories)
    {
        $nodeBuilder = $node
            ->children()
                ->arrayNode($plural)
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->canBeUnset()
                        ->validate()
                            ->ifTrue(fn($v) => \count($v) > 1)
                            ->thenInvalid(\sprintf('Can only have 1 %s per configuration.', $singular))
                        ->end()
                        ->children()
        ;

        foreach ($factories as $name => $factory) {
            $factoryNode = $nodeBuilder->arrayNode($name);

            $factory->addConfiguration($factoryNode);
        }
    }
}
