<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Namer;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\Backup\Namer\SimpleNamer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SimpleNamerFactory implements Factory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'simple';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $serviceId = sprintf('zenstruck_backup.namer.%s', $id);

        $container->setDefinition($serviceId, new ChildDefinition('zenstruck_backup.namer.abstract_simple'))
            ->replaceArgument(0, $config['name'])
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
                ->scalarNode('name')->defaultValue(SimpleNamer::DEFAULT_NAME)->end()
            ->end()
        ;
    }
}
