<?php

namespace Zenstruck\BackupBundle\Tests\Command;

use Zenstruck\Backup\Console\Command\ProfileActionCommand;
use Zenstruck\BackupBundle\Command\ListCommand;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ListCommandTest extends ProfileActionCommandTest
{
    protected function createCommand(): ListCommand|ProfileActionCommand
    {
        return new ListCommand();
    }

    protected function getCommandName(): string
    {
        return 'zenstruck:backup:list';
    }
}
