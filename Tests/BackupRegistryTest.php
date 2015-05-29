<?php

namespace Zenstruck\BackupBundle\Tests;

use Zenstruck\BackupBundle\BackupRegistry;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupRegistryTest extends BaseTest
{
    public function testGetAndAll()
    {
        $registry = new BackupRegistry();
        $registry->add('foo', $this->createNullBackupManager());

        $this->assertInstanceOf('Zenstruck\BackupBundle\BackupManager', $registry->get('foo'));
        $this->assertCount(1, $registry->all());
    }

    public function testInvalidGet()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $registry = new BackupRegistry();
        $registry->add('foo', $this->createNullBackupManager());

        $registry->get('bar');
    }
}
