<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Processor;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\BackupBundle\Processor\GzipArchiveProcessor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GzipArchiveProcessorFactory implements Factory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gzip';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $id = sprintf('zenstruck_backup.processor.%s', $id);

        $container->setDefinition($id, new DefinitionDecorator('zenstruck_backup.processor.abstract_gzip'))
            ->replaceArgument(0, $config['options'])
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
                ->scalarNode('options')->defaultValue(GzipArchiveProcessor::DEFAULT_OPTIONS)->end()
            ->end()
        ;
    }
}
