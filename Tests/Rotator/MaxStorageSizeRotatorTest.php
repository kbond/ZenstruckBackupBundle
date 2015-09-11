<?php

namespace Zenstruck\BackupBundle\Tests\Rotator;

use Zenstruck\BackupBundle\Destination\Backup;
use Zenstruck\BackupBundle\Rotator\MaxStorageSizeRotator;

class MaxStorageSizeRotatorTest extends \PHPUnit_Framework_TestCase
{
    public function testRotator()
    {
        $rotator = new MaxStorageSizeRotator(1000);

        $backups = array(
            $b5 = new Backup(5, 5, 500, \DateTime::createFromFormat('Y-m-d', '2000-01-05')),
            $b1 = new Backup(1, 1, 100, \DateTime::createFromFormat('Y-m-d', '2000-01-01')),
            $b4 = new Backup(4, 4, 400, \DateTime::createFromFormat('Y-m-d', '2000-01-04')),
            $b3 = new Backup(3, 3, 300, \DateTime::createFromFormat('Y-m-d', '2000-01-03')),
            $b2 = new Backup(2, 2, 200, \DateTime::createFromFormat('Y-m-d', '2000-01-02'))
        );

        $nominations = $rotator->nominate($backups);

        $this->assertEquals(3, count($nominations), 'There should be 3 nominations for rotation.');
        $this->assertTrue(in_array($b1, $nominations), 'Backup 1 should be nominated for removal.');
        $this->assertTrue(in_array($b2, $nominations), 'Backup 2 should be nominated for removal.');
        $this->assertTrue(in_array($b3, $nominations), 'Backup 3 should be nominated for removal.');
    }

    public function testInvalidSizeFormat()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Invalid size format');
        new MaxStorageSizeRotator('89_mb');
    }

    public function testUnknownSizeFormat()
    {
        $this->setExpectedException('\InvalidArgumentException', 'Unknown size format');
        new MaxStorageSizeRotator('invalid');
    }
}