<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Source;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\Backup\Source\MySqlDumpSource;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class MySqlDumpSourceFactory implements Factory
{
    public function getName(): string
    {
        return 'mysqldump';
    }

    public function create(ContainerBuilder $container, string $id, array $config): Reference
    {
        $serviceId = \sprintf('zenstruck_backup.source.%s', $id);

        $container->setDefinition($serviceId, new ChildDefinition('zenstruck_backup.source.abstract_mysqldump'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['database'])
            ->replaceArgument(2, $config['host'])
            ->replaceArgument(3, $config['user'])
            ->replaceArgument(4, $config['password'])
            ->replaceArgument(5, $config['ssh_host'])
            ->replaceArgument(6, $config['ssh_user'])
            ->replaceArgument(7, $config['ssh_port'])
            ->replaceArgument(8, $config['timeout'])
            ->addTag('zenstruck_backup.source')
        ;

        return new Reference($serviceId);
    }

    public function addConfiguration(ArrayNodeDefinition $builder): void
    {
        $builder
            ->children()
                ->scalarNode('database')->isRequired()->end()
                ->scalarNode('host')->defaultNull()->end()
                ->scalarNode('user')->defaultValue(MySqlDumpSource::DEFAULT_USER)->end()
                ->scalarNode('password')->defaultNull()->end()
                ->scalarNode('ssh_host')->defaultNull()->end()
                ->scalarNode('ssh_user')->defaultNull()->end()
                ->scalarNode('ssh_port')->defaultValue(MySqlDumpSource::DEFAULT_SSH_PORT)->end()
                ->integerNode('timeout')->defaultValue(MySqlDumpSource::DEFAULT_TIMEOUT)->end()
            ->end()
        ;
    }
}
