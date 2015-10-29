<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\DestinationCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DestinationCompilerPassTest extends RegisterCompilerPassTest
{
    /**
     * {@inheritdoc}
     */
    protected function getRegistrarDefinitionName()
    {
        return 'zenstruck_backup.profile_builder';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTagName()
    {
        return 'zenstruck_backup.destination';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodName()
    {
        return 'addDestination';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DestinationCompilerPass());
    }
}
