<?php

namespace Zenstruck\BackupBundle\Tests;

use Zenstruck\BackupBundle\ZenstruckBackupBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckBackupBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function compiler_passes_are_registered()
    {
        $container = $this
            ->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->setMethods(array('addCompilerPass'))
            ->getMock();

        $container
            ->expects($this->exactly(5))
            ->method('addCompilerPass')
            ->withConsecutive(
                $this->isInstanceOf('Zenstruck\BackupBundle\DependencyInjection\Compiler\ProfileCompilerPass'),
                $this->isInstanceOf('Zenstruck\BackupBundle\DependencyInjection\Compiler\DestinationCompilerPass'),
                $this->isInstanceOf('Zenstruck\BackupBundle\DependencyInjection\Compiler\SourceCompilerPass'),
                $this->isInstanceOf('Zenstruck\BackupBundle\DependencyInjection\Compiler\ProcessorCompilerPass'),
                $this->isInstanceOf('Zenstruck\BackupBundle\DependencyInjection\Compiler\NamerCompilerPass')
            );

        $bundle = new ZenstruckBackupBundle();
        $bundle->build($container);
    }
}
