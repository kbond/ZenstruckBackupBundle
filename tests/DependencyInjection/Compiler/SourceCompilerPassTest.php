<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\SourceCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SourceCompilerPassTest extends RegisterCompilerPassTest
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
        return 'zenstruck_backup.source';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodName()
    {
        return 'addSource';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SourceCompilerPass());
    }
}
