<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Destination;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\Backup\Destination\AwsCliS3Destination;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class AwsCliS3DestinationFactory implements Factory
{
    public function getName(): string
    {
        return 'aws_cli_s3';
    }

    public function create(ContainerBuilder $container, string $id, array $config): Reference
    {
        $serviceId = \sprintf('zenstruck_backup.destination.%s', $id);

        $container->setDefinition($serviceId, new ChildDefinition('zenstruck_backup.destination.abstract_aws_cli_s3'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['bucket'])
            ->replaceArgument(2, $config['timeout'])
            ->replaceArgument(3, $config['options'])
            ->addTag('zenstruck_backup.destination')
        ;

        return new Reference($serviceId);
    }

    public function addConfiguration(ArrayNodeDefinition $builder): void
    {
        $builder
            ->children()
                ->scalarNode('bucket')->isRequired()->example('s3://foobar/backups')->end()
                ->integerNode('timeout')->defaultValue(AwsCliS3Destination::DEFAULT_TIMEOUT)->end()
                ->arrayNode('options')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;
    }
}
