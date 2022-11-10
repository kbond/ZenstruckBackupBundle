<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Compiler;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProcessorCompilerPass extends RegisterCompilerPass
{
    protected function getDefinitionName(): string
    {
        return 'zenstruck_backup.profile_builder';
    }

    protected function getTagName(): string
    {
        return 'zenstruck_backup.processor';
    }

    protected function getMethodName(): string
    {
        return 'addProcessor';
    }
}
