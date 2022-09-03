<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\DestinationCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DestinationCompilerPassTest extends RegisterCompilerPassTest
{
    protected function getRegistrarDefinitionName(): string
    {
        return 'zenstruck_backup.profile_builder';
    }

    protected function getTagName(): string
    {
        return 'zenstruck_backup.destination';
    }

    protected function getMethodName(): string
    {
        return 'addDestination';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DestinationCompilerPass());
    }
}
