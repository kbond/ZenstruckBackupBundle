<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProcessorCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProcessorCompilerPassTest extends RegisterCompilerPassTest
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
        return 'zenstruck_backup.processor';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodName()
    {
        return 'addProcessor';
    }

    /**
     * {@inheritdoc}
     */
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProcessorCompilerPass());
    }
}
