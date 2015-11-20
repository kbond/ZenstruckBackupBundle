<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Processor;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\Backup\Processor\ZipArchiveProcessor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZipArchiveProcessorFactory implements Factory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'zip';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $serviceId = sprintf('zenstruck_backup.processor.%s', $id);

        $container->setDefinition($serviceId, new DefinitionDecorator('zenstruck_backup.processor.abstract_zip'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['options'])
            ->replaceArgument(2, $config['timeout'])
            ->addTag('zenstruck_backup.processor')
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
                ->scalarNode('options')->defaultValue(ZipArchiveProcessor::DEFAULT_OPTIONS)->end()
                ->integerNode('timeout')->defaultValue(ZipArchiveProcessor::DEFAULT_TIMEOUT)->end()
            ->end()
        ;
    }
}
