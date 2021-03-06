<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Compiler;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProfileCompilerPass extends RegisterCompilerPass
{
    /**
     * {@inheritdoc}
     */
    protected function getDefinitionName()
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
}
