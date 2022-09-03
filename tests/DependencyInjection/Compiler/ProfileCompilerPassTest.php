<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProfileCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProfileCompilerPassTest extends RegisterCompilerPassTest
{
    protected function getRegistrarDefinitionName(): string
    {
        return 'zenstruck_backup.profile_registry';
    }

    protected function getTagName(): string
    {
        return 'zenstruck_backup.profile';
    }

    protected function getMethodName(): string
    {
        return 'add';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ProfileCompilerPass());
    }
}
