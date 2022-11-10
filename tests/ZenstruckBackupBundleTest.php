<?php

namespace Zenstruck\BackupBundle\Tests;

use PHPUnit\Framework\TestCase;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\DestinationCompilerPass;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\NamerCompilerPass;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProcessorCompilerPass;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProfileCompilerPass;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\SourceCompilerPass;
use Zenstruck\BackupBundle\ZenstruckBackupBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckBackupBundleTest extends TestCase
{
    /**
     * @test
     */
    public function compiler_passes_are_registered()
    {
        $container = $this
            ->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->setMethods(['addCompilerPass'])
            ->getMock()
        ;

        $container
            ->expects($this->exactly(5))
            ->method('addCompilerPass')
            ->withConsecutive(
                [$this->isInstanceOf(ProfileCompilerPass::class)],
                [$this->isInstanceOf(DestinationCompilerPass::class)],
                [$this->isInstanceOf(SourceCompilerPass::class)],
                [$this->isInstanceOf(ProcessorCompilerPass::class)],
                [$this->isInstanceOf(NamerCompilerPass::class)]
            )
        ;

        $bundle = new ZenstruckBackupBundle();
        $bundle->build($container);
    }
}
