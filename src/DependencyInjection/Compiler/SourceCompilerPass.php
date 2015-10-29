<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Compiler;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SourceCompilerPass extends RegisterCompilerPass
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
        return 'zenstruck_backup.source';
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethodName()
    {
        return 'addSource';
    }
}
