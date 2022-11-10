<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProcessorCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProcessorCompilerPassTest extends RegisterCompilerPassTest
{
    protected function getRegistrarDefinitionName(): string
    {
        return 'zenstruck_backup.profile_builder';
    }

    protected function getTagName(): string
    {
        return 'zenstruck_backup.processor';
    }

    protected function getMethodName(): string
    {
        return 'addProcessor';
    }

    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ProcessorCompilerPass());
    }
}
