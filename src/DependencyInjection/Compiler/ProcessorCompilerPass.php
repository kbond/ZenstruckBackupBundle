<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Compiler;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProcessorCompilerPass extends RegisterCompilerPass
{
    /**
     * {@inheritdoc}
     */
    protected function getDefinitionName()
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
}
