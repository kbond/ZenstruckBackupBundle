<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProfileCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProfileCompilerPassTest extends RegisterCompilerPassTest
{
    /**
     * {@inheritdoc}
     */
    protected function getRegistrarDefinitionName()
    {
        return 'zenstruck_backup.profile_registry';
    }

    /**
     * {@inheritdoc}
     */
    protected function getTagName()
    {
        return 'zenstruck_backup.profile';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodName()
    {
        return 'add';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProfileCompilerPass());
    }
}
