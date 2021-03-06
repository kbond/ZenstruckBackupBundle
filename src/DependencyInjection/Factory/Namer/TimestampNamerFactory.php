<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Namer;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\Backup\Namer\TimestampNamer;

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
        $serviceId = sprintf('zenstruck_backup.namer.%s', $id);

        $container->setDefinition($serviceId, new DefinitionDecorator('zenstruck_backup.namer.abstract_timestamp'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['format'])
            ->replaceArgument(2, $config['prefix'])
            ->replaceArgument(3, $config['timezone'])
            ->addTag('zenstruck_backup.namer')
        ;

        return new Reference($serviceId);
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
