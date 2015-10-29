<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Destination;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\Backup\Destination\S3CmdDestination;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class S3CmdDestinationFactory implements Factory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 's3cmd';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $serviceId = sprintf('zenstruck_backup.destination.%s', $id);

        $container->setDefinition($serviceId, new DefinitionDecorator('zenstruck_backup.destination.abstract_s3cmd'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['bucket'])
            ->replaceArgument(2, $config['timeout'])
            ->replaceArgument(3, $config['options'])
            ->addTag('zenstruck_backup.destination')
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
                ->scalarNode('bucket')->isRequired()->example('s3://foobar/backups')->end()
                ->integerNode('timeout')->defaultValue(S3CmdDestination::DEFAULT_TIMEOUT)->end()
                ->arrayNode('options')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;
    }
}
