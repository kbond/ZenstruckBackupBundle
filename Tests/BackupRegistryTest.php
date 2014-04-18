<?php

namespace Zenstruck\BackupBundle\Tests;

use Zenstruck\BackupBundle\BackupRegistry;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupRegistryTest extends BaseTest
{
    public function testGet()
    {
        $registry = new BackupRegistry();
        $registry->add('foo', $this->createNullBackupManager());

        $this->assertInstanceOf('Zenstruck\BackupBundle\BackupManager', $registry->get('foo'));
    }

    public function testInvalidGet()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $registry = new BackupRegistry();
        $registry->add('foo', $this->createNullBackupManager());

        $registry->get('bar');
    }
}
