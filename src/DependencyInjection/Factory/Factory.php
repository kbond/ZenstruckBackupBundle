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
    /**
     * @return string
     */
    public function getName();

    /**
     * Creates the reference, registers it and returns a reference.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     * @param string           $id        The id of the service
     * @param array            $config    An array of configuration
     *
     * @return Reference
     */
    public function create(ContainerBuilder $container, $id, array $config);

    /**
     * Adds configuration nodes for the factory.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function addConfiguration(ArrayNodeDefinition $builder);
}
