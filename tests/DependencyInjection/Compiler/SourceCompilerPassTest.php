<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\SourceCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SourceCompilerPassTest extends RegisterCompilerPassTest
{
    protected function getRegistrarDefinitionName(): string
    {
        return 'zenstruck_backup.profile_builder';
    }

    protected function getTagName(): string
    {
        return 'zenstruck_backup.source';
    }

    protected function getMethodName(): string
    {
        return 'addSource';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new SourceCompilerPass());
    }
}
