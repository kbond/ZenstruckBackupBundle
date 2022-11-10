<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory\Destination;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class StreamDestinationFactory implements Factory
{
    public function getName(): string
    {
        return 'stream';
    }

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, string $id, array $config): Reference
    {
        $serviceId = sprintf('zenstruck_backup.destination.%s', $id);

        $container->setDefinition($serviceId, new ChildDefinition('zenstruck_backup.destination.abstract_stream'))
            ->replaceArgument(0, $id)
            ->replaceArgument(1, $config['directory'])
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
                ->scalarNode('directory')->isRequired()->end()
            ->end()
        ;
    }
}
