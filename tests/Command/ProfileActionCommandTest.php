<?php

namespace Zenstruck\BackupBundle\Tests\Command;

use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Console\Application as FrameworkApplication;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Zenstruck\Backup\Console\Command\ProfileActionCommand;
use Zenstruck\Backup\Executor;
use Zenstruck\Backup\ProfileRegistry;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ProfileActionCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage No profiles configured.
     */
    public function it_can_execute()
    {
        $registry = new ProfileRegistry();
        $executor = new Executor(new NullLogger());

        $container = $this->createMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(array('zenstruck_backup.profile_registry'), array('zenstruck_backup.executor'))
            ->willReturnOnConsecutiveCalls($registry, $executor);

        $kernel = $this->createMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->expects($this->any())
            ->method('getContainer')
            ->willReturn($container);

        $kernel->expects($this->any())
            ->method('getBundles')
            ->willReturn(array());

        $application = new FrameworkApplication($kernel);
        $application->add($this->createCommand());

        $tester = new CommandTester($application->find($this->getCommandName()));
        $tester->execute(array('command' => $this->getCommandName()));
    }

    /**
     * @test
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Application must be instance of Symfony\Bundle\FrameworkBundle\Console\Application
     */
    public function it_fails_with_wrong_application()
    {
        $application = new Application($this->createMock('Symfony\Component\DependencyInjection\ContainerInterface'));
        $application->add($this->createCommand());

        $tester = new CommandTester($application->find($this->getCommandName()));
        $tester->execute(array('command' => $this->getCommandName()));
    }

    /**
     * @return ProfileActionCommand
     */
    abstract protected function createCommand();

    /**
     * @return string
     */
    abstract protected function getCommandName();
}
