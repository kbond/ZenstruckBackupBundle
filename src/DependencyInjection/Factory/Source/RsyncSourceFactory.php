<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Source;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\Backup\Source\RsyncSource;

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
        $serviceId = sprintf('zenstruck_backup.source.%s', $id);

        $container->setDefinition($serviceId, new DefinitionDecorator('zenstruck_backup.source.abstract_rsync'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['source'])
            ->replaceArgument(2, $config['additional_options'])
            ->replaceArgument(3, $config['default_options'])
            ->addTag('zenstruck_backup.source')
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
