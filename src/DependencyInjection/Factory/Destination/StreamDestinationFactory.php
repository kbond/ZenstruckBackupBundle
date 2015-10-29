<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Destination;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class StreamDestinationFactory implements Factory
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'stream';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, array $config)
    {
        $serviceId = sprintf('zenstruck_backup.destination.%s', $id);

        $container->setDefinition($serviceId, new DefinitionDecorator('zenstruck_backup.destination.abstract_stream'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['directory'])
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
                ->scalarNode('directory')->isRequired()->end()
            ->end()
        ;
    }
}
