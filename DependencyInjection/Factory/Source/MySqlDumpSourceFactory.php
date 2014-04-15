<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Source;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;
use Zenstruck\BackupBundle\Source\MySqlDumpSource;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class MySqlDumpSourceFactory implements Factory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mysqldump';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $id = sprintf('zenstruck_backup.source.%s', $id);

        $container->setDefinition($id, new DefinitionDecorator('zenstruck_backup.source.abstract_mysqldump'))
            ->replaceArgument(0, $config['database'])
            ->replaceArgument(1, $config['user'])
            ->replaceArgument(2, $config['password'])
            ->replaceArgument(3, $config['ssh_host'])
            ->replaceArgument(4, $config['ssh_user'])
            ->replaceArgument(5, $config['ssh_port'])
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
                ->scalarNode('database')->isRequired()->end()
                ->scalarNode('user')->defaultValue(MySqlDumpSource::DEFAULT_USER)->end()
                ->scalarNode('password')->defaultNull()->end()
                ->scalarNode('ssh_host')->defaultNull()->end()
                ->scalarNode('ssh_user')->defaultNull()->end()
                ->scalarNode('ssh_port')->defaultValue(MySqlDumpSource::DEFAULT_SSH_PORT)->end()
            ->end()
        ;
    }
}
