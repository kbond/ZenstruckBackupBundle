<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\NamerCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class NamerCompilerPassTest extends RegisterCompilerPassTest
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
        return 'zenstruck_backup.namer';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodName()
    {
        return 'addNamer';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new NamerCompilerPass());
    }
}
