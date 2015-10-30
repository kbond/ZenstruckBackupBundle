<?php

namespace Zenstruck\BackupBundle\Tests\Command;

use Zenstruck\BackupBundle\Command\ListCommand;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ListCommandTest extends ProfileActionCommandTest
{
    /**
     * {@inheritdoc}
     */
    protected function createCommand()
    {
        return new ListCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName()
    {
        return 'zenstruck:backup:list';
    }

}
