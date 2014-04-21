<?php

namespace Zenstruck\BackupBundle\Tests\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Zenstruck\BackupBundle\BackupRegistry;
use Zenstruck\BackupBundle\Command\BackupCommand;
use Zenstruck\BackupBundle\Tests\BaseTest;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupCommandTest extends BaseTest
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester(2);
        $commandTester->execute(
            array('command' => 'zenstruck:backup', 'profile' => 'foo')
        );
    }

    public function testExecuteWithClear()
    {
        $commandTester = $this->createCommandTester(3);
        $commandTester->execute(
            array('command' => 'zenstruck:backup', 'profile' => 'foo', '--clear' => true)
        );
    }

    private function createCommandTester($infoCalls)
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->exactly($infoCalls))
            ->method('info')
        ;

        $registry = new BackupRegistry();
        $registry->add('foo', $this->createNullBackupManager($logger));
        $application = new Application();
        $application->add(new BackupCommand($registry));

        $command = $application->find('zenstruck:backup');

        return new CommandTester($command);
    }
}
