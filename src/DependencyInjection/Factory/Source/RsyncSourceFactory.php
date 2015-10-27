<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Source;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\BackupBundle\Source\RsyncSource;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RsyncSourceFactory implements Factory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'rsync';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $id = sprintf('zenstruck_backup.source.%s', $id);

        $container->setDefinition($id, new DefinitionDecorator('zenstruck_backup.source.abstract_rsync'))
            ->replaceArgument(0, $config['source'])
            ->replaceArgument(1, $config['additional_options'])
            ->replaceArgument(2, $config['default_options'])
        ;

        return new Reference($id);
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('source')->isRequired()->end()
                ->arrayNode('additional_options')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('default_options')
                    ->prototype('scalar')->end()
                    ->defaultValue(RsyncSource::getDefaultOptions())
                ->end()
            ->end()
        ;
    }
}
