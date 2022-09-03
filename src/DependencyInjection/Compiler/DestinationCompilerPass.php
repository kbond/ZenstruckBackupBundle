<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Compiler;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DestinationCompilerPass extends RegisterCompilerPass
{
    protected function getDefinitionName(): string
    {
        return 'zenstruck_backup.profile_builder';
    }

    protected function getTagName(): string
    {
        return 'zenstruck_backup.destination';
    }

    protected function getMethodName(): string
    {
        return 'addDestination';
    }
}
