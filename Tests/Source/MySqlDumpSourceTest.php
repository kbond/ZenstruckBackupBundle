<?php

namespace Zenstruck\BackupBundle\Tests\Source;

use Psr\Log\NullLogger;
use Zenstruck\BackupBundle\Source\MySqlDumpSource;
use Zenstruck\BackupBundle\Tests\BaseTest;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class MySqlDumpSourceTest extends BaseTest
{
    public function testFetch()
    {
        $scratch = $this->getScratchDir();
        $file = $scratch.'/zenstruck_backup.sql';
        $source = new MySqlDumpSource('zenstruck_backup');

        $this->assertFileNotExists($file);

        $source->fetch($scratch, new NullLogger());

        $this->assertFileExists($file);
        $this->assertContains('Database: zenstruck_backup', file_get_contents($file));
    }

    public function testInvalidDatabase()
    {
        $this->setExpectedException('\RuntimeException', "mysqldump: Got error: 1049: Unknown database 'foobar' when selecting the database");

        $scratch = $this->getScratchDir();
        $source = new MySqlDumpSource('foobar');

        $source->fetch($scratch, new NullLogger());
    }
}
