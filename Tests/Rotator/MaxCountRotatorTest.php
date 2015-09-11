<?php

namespace Zenstruck\BackupBundle\Tests\Rotator;

use Zenstruck\BackupBundle\Destination\Backup;
use Zenstruck\BackupBundle\Rotator\MaxCountRotator;

class MaxCountRotatorTest extends \PHPUnit_Framework_TestCase
{
    public function testRotator()
    {
        $rotator = new MaxCountRotator(3);

        $backups = array(
            $b5 = new Backup(5, 5, 5, \DateTime::createFromFormat('Y-m-d', '2000-01-05')),
            $b1 = new Backup(1, 1, 1, \DateTime::createFromFormat('Y-m-d', '2000-01-01')),
            $b4 = new Backup(4, 4, 4, \DateTime::createFromFormat('Y-m-d', '2000-01-04')),
            $b3 = new Backup(3, 3, 3, \DateTime::createFromFormat('Y-m-d', '2000-01-03')),
            $b2 = new Backup(2, 2, 2, \DateTime::createFromFormat('Y-m-d', '2000-01-02'))
        );

        $nominations = $rotator->nominate($backups);

        $this->assertEquals(2, count($nominations), 'There should be 2 nominations for rotation.');
        $this->assertTrue(in_array($b1, $nominations), 'Backup 1 should be nominated for removal.');
        $this->assertTrue(in_array($b2, $nominations), 'Backup 2 should be nominated for removal.');
    }

    public function testInvalidCount()
    {
        $this->setExpectedException('\InvalidArgumentException', 'You need to allow at least one backup file to be created.');
        new MaxCountRotator(-1);
    }
}