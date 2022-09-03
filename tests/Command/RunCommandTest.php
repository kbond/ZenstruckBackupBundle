<?php

namespace Zenstruck\BackupBundle\Tests\Command;

use Zenstruck\BackupBundle\Command\RunCommand;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RunCommandTest extends ProfileActionCommandTest
{
    protected function createCommand(): RunCommand
    {
        return new RunCommand();
    }

    protected function getCommandName(): string
    {
        return 'zenstruck:backup:run';
    }
}
