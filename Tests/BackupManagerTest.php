<?php

namespace Zenstruck\BackupBundle\Tests;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupManagerTest extends BaseTest
{
    public function testBackup()
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->exactly(2))
            ->method('info')
        ;

        $manager = $this->createNullBackupManager($logger);

        $manager->backup();
    }

    public function testBackupWithClear()
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->exactly(3))
            ->method('info')
        ;

        $manager = $this->createNullBackupManager($logger);

        $manager->backup(true);
    }
}
