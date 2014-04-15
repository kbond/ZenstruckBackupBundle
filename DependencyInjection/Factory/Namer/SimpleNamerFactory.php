<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Namer;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\BackupBundle\Namer\SimpleNamer;

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
        $id = sprintf('zenstruck_backup.namer.%s', $id);

        $container->setDefinition($id, new DefinitionDecorator('zenstruck_backup.namer.abstract_simple'))
            ->replaceArgument(0, $config['name'])
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
                ->scalarNode('name')->defaultValue(SimpleNamer::DEFAULT_NAME)->end()
            ->end()
        ;
    }
}
