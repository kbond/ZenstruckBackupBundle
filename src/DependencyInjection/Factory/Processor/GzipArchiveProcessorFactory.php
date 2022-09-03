<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Processor;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\Backup\Processor\ArchiveProcessor;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\Backup\Processor\GzipArchiveProcessor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GzipArchiveProcessorFactory implements Factory
{
    public function getName(): string
    {
        return 'gzip';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, string $id, array $config): Reference
    {
        $serviceId = sprintf('zenstruck_backup.processor.%s', $id);

        $container->setDefinition($serviceId, new ChildDefinition('zenstruck_backup.processor.abstract_gzip'))
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
                ->scalarNode('options')->defaultValue(GzipArchiveProcessor::DEFAULT_OPTIONS)->end()
                ->integerNode('timeout')->defaultValue(ArchiveProcessor::DEFAULT_TIMEOUT)->end()
            ->end()
        ;
    }
}
