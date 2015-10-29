<?php

namespace Zenstruck\BackupBundle\Tests\Command;

use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Console\Application as FrameworkApplication;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Zenstruck\Backup\Executor;
use Zenstruck\Backup\ProfileRegistry;
use Zenstruck\BackupBundle\Command\BackupCommand;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupCommandTest extends \PHPUnit_Framework_TestCase
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

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $container->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(array('zenstruck_backup.profile_registry'), array('zenstruck_backup.executor'))
            ->willReturnOnConsecutiveCalls($registry, $executor);

        $kernel = $this->getMock('Symfony\Component\HttpKernel\KernelInterface');
        $kernel->expects($this->once())
            ->method('getContainer')
            ->willReturn($container);

        $application = new FrameworkApplication($kernel);
        $application->add(new BackupCommand());

        $tester = new CommandTester($application->find('zenstruck:backup'));
        $tester->execute(array('command' => 'zenstruck:backup'));
    }

    /**
     * @test
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Application must be instance of Symfony\Bundle\FrameworkBundle\Console\Application
     */
    public function it_fails_with_wrong_application()
    {
        $application = new Application($this->getMock('Symfony\Component\DependencyInjection\ContainerInterface'));
        $application->add(new BackupCommand());

        $tester = new CommandTester($application->find('zenstruck:backup'));
        $tester->execute(array('command' => 'zenstruck:backup'));
    }
}
