<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Compiler;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProfileCompilerPass extends RegisterCompilerPass
{
    protected function getDefinitionName(): string
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
}
