<?php

namespace Zenstruck\BackupBundle\Tests\Command;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Console\Application as FrameworkApplication;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zenstruck\Backup\Console\Command\ProfileActionCommand;
use Zenstruck\Backup\Executor;
use Zenstruck\Backup\ProfileRegistry;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ProfileActionCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_execute()
    {
        $this->expectExceptionMessage("No profiles configured.");
        $this->expectException(\RuntimeException::class);
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
     */
    public function it_fails_with_wrong_application()
    {
        $this->expectExceptionMessage("Application must be instance of Symfony\Bundle\FrameworkBundle\Console\Application");
        $this->expectException(\RuntimeException::class);

        # TODO is this Change correct ? Useful anymore?
        /** @var ContainerInterface&MockObject $container */
        $container = $this->createMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $application = new Application("avc");
        $application->add($this->createCommand());

        $tester = new CommandTester($application->find($this->getCommandName()));
        $tester->execute(array('command' => $this->getCommandName()));
    }

    abstract protected function createCommand(): ProfileActionCommand;

    abstract protected function getCommandName(): string;
}
