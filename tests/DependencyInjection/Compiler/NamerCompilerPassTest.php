<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\NamerCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class NamerCompilerPassTest extends RegisterCompilerPassTest
{
    protected function getRegistrarDefinitionName(): string
    {
        return 'zenstruck_backup.profile_builder';
    }

    protected function getTagName(): string
    {
        return 'zenstruck_backup.namer';
    }

    protected function getMethodName(): string
    {
        return 'addNamer';
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new NamerCompilerPass());
    }
}
