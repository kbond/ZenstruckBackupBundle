<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Destination;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\Backup\Destination\S3CmdDestination;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class S3CmdDestinationFactory implements Factory
{
    public function getName(): string
    {
        return 's3cmd';
    }

    public function create(ContainerBuilder $container, string $id, array $config): Reference
    {
        $serviceId = \sprintf('zenstruck_backup.destination.%s', $id);

        $container->setDefinition($serviceId, new ChildDefinition('zenstruck_backup.destination.abstract_s3cmd'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['bucket'])
            ->replaceArgument(2, $config['timeout'])
            ->replaceArgument(3, $config['options'])
            ->addTag('zenstruck_backup.destination')
        ;

        return new Reference($serviceId);
    }

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
