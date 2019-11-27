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
    private $namerFactories;
    private $processorFactories;
    private $sourceFactories;
    private $destinationFactories;

    /**
     * @param Factory[] $namerFactories
     * @param Factory[] $processorFactories
     * @param Factory[] $sourceFactories
     * @param Factory[] $destinationFactories
     */
    public function __construct(array $namerFactories, array $processorFactories, array $sourceFactories, array $destinationFactories)
    {
        $this->namerFactories = $namerFactories;
        $this->processorFactories = $processorFactories;
        $this->sourceFactories = $sourceFactories;
        $this->destinationFactories = $destinationFactories;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('zenstruck_backup');

        // Keep compatibility with symfony/config < 4.2
        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            $rootNode = $treeBuilder->root('zenstruck_backup');
        }

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
                                    ->then(function ($v) {
                                        return array($v);
                                    })
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
                                    ->then(function ($v) {
                                        return array($v);
                                    })
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @param string $id
     *
     * @return Factory
     */
    public function getSourceFactory($id)
    {
        return $this->sourceFactories[$id];
    }

    /**
     * @param string $id
     *
     * @return Factory
     */
    public function getNamerFactory($id)
    {
        return $this->namerFactories[$id];
    }

    /**
     * @param string $id
     *
     * @return Factory
     */
    public function getDestinationFactory($id)
    {
        return $this->destinationFactories[$id];
    }

    /**
     * @param string $id
     *
     * @return Factory
     */
    public function getProcessorFactory($id)
    {
        return $this->processorFactories[$id];
    }

    /**
     * @param ArrayNodeDefinition $node
     * @param string              $plural
     * @param string              $singular
     * @param Factory[]           $factories
     */
    private function addFactories(ArrayNodeDefinition $node, $plural, $singular, array $factories)
    {
        $nodeBuilder = $node
            ->children()
                ->arrayNode($plural)
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->canBeUnset()
                        ->validate()
                            ->ifTrue(function ($v) {
                                return count($v) > 1;
                            })
                            ->thenInvalid(sprintf('Can only have 1 %s per configuration.', $singular))
                        ->end()
                        ->children()
        ;

        foreach ($factories as $name => $factory) {
            $factoryNode = $nodeBuilder->arrayNode($name);

            $factory->addConfiguration($factoryNode);
        }
    }
}
