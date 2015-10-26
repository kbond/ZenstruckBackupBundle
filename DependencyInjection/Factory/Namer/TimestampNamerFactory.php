<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Namer;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\BackupBundle\Namer\TimestampNamer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TimestampNamerFactory implements Factory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'timestamp';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $id = sprintf('zenstruck_backup.namer.%s', $id);

        $container->setDefinition($id, new DefinitionDecorator('zenstruck_backup.namer.abstract_timestamp'))
            ->replaceArgument(0, $config['format'])
            ->replaceArgument(1, $config['prefix'])
            ->replaceArgument(2, $config['timezone'])
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
                ->scalarNode('format')->defaultValue(TimestampNamer::DEFAULT_FORMAT)->end()
                ->scalarNode('prefix')->defaultValue(TimestampNamer::DEFAULT_PREFIX)->end()
                ->scalarNode('timezone')->defaultNull()->end()
            ->end()
        ;
    }
}
