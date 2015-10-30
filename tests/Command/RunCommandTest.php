<?php

namespace Zenstruck\BackupBundle\Tests\Command;

use Zenstruck\BackupBundle\Command\RunCommand;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RunCommandTest extends ProfileActionCommandTest
{
    /**
     * {@inheritdoc}
     */
    protected function createCommand()
    {
        return new RunCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName()
    {
        return 'zenstruck:backup:run';
    }

}
