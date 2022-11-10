<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Factory;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Factory
{
    public function getName(): string;

    /**
     * Creates the reference, registers it and returns a reference.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     * @param string           $id        The id of the service
     * @param array            $config    An array of configuration
     */
    public function create(ContainerBuilder $container, string $id, array $config): Reference;

    /**
     * Adds configuration nodes for the factory.
     */
    public function addConfiguration(ArrayNodeDefinition $builder);
}
