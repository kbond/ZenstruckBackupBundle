<?php

namespace Zenstruck\BackupBundle\Tests;

use Zenstruck\BackupBundle\ZenstruckBackupBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckBackupBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testCompilerPassesAreRegistered()
    {
        $container = $this
            ->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
            ->setMethods(array('addCompilerPass'))
            ->getMock();
        $container
            ->expects($this->atLeastOnce())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf('Symfony\\Component\\DependencyInjection\\Compiler\\CompilerPassInterface'));

        $bundle = new ZenstruckBackupBundle();
        $bundle->build($container);
    }
}
