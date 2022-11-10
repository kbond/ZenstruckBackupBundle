<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Source;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\Backup\Source\RsyncSource;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RsyncSourceFactory implements Factory
{
    public function getName(): string
    {
        return 'rsync';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, string $id, array $config): Reference
    {
        $serviceId = sprintf('zenstruck_backup.source.%s', $id);

        $container->setDefinition($serviceId, new ChildDefinition('zenstruck_backup.source.abstract_rsync'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['source'])
            ->replaceArgument(2, $config['additional_options'])
            ->replaceArgument(3, $config['default_options'])
            ->replaceArgument(4, $config['timeout'])
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
                ->integerNode('timeout')->defaultValue(RsyncSource::DEFAULT_TIMEOUT)->end()
            ->end()
        ;
    }
}
