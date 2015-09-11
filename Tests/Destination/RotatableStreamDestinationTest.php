<?php

namespace Zenstruck\BackupBundle\Tests\Destination;

use Psr\Log\NullLogger;
use DateTime;
use Zenstruck\BackupBundle\Destination\RotatableStreamDestination;
use Zenstruck\BackupBundle\Rotator\MaxCountRotator;
use Zenstruck\BackupBundle\Tests\BaseTest;

class RotatableStreamDestinationTest extends BaseTest
{
    public function testPreRotationPush()
    {
        $destination = new RotatableStreamDestination($this->getScratchDir(), new MaxCountRotator(3));
        $this->executeTests($destination);
    }

    public function testPostRotationPush()
    {
        $destination = new RotatableStreamDestination($this->getScratchDir(), null, new MaxCountRotator(3));
        $this->executeTests($destination);
    }

    public function testPreAndPostRotationPush()
    {
        $destination = new RotatableStreamDestination($this->getScratchDir(),  new MaxCountRotator(3), new MaxCountRotator(3));
        $this->executeTests($destination);
    }

    private function executeTests(RotatableStreamDestination $destination)
    {
        $this->setUp();

        $file1 = $this->getFixtureDir().'/rotation/1.txt';
        $file2 = $this->getFixtureDir().'/rotation/2.txt';
        $file3 = $this->getFixtureDir().'/rotation/3.txt';
        $file4 = $this->getFixtureDir().'/rotation/4.txt';
        $file5 = $this->getFixtureDir().'/rotation/5.txt';

        touch($file1, DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:00:00')->getTimestamp());
        touch($file2, DateTime::createFromFormat('Y-m-d H:i:s', '2011-01-01 00:00:00')->getTimestamp());
        touch($file3, DateTime::createFromFormat('Y-m-d H:i:s', '2012-01-01 00:00:00')->getTimestamp());
        touch($file4, DateTime::createFromFormat('Y-m-d H:i:s', '2013-01-01 00:00:00')->getTimestamp());
        touch($file5, DateTime::createFromFormat('Y-m-d H:i:s', '2014-01-01 00:00:00')->getTimestamp());

        $destinationDir = $this->getScratchDir();
        $logger = new NullLogger();

        $destinationFile1 = $destinationDir.'/1.txt';
        $destinationFile2 = $destinationDir.'/2.txt';
        $destinationFile3 = $destinationDir.'/3.txt';
        $destinationFile4 = $destinationDir.'/4.txt';
        $destinationFile5 = $destinationDir.'/5.txt';

        $this->assertFileNotExists($destinationFile1);
        $destination->push($file1, $logger);
        $this->assertFileExists($destinationFile1);

        $this->assertFileNotExists($destinationFile2);
        $destination->push($file2, $logger);
        $this->assertFileExists($destinationFile1);
        $this->assertFileExists($destinationFile2);

        $this->assertFileNotExists($destinationFile3);
        $destination->push($file3, $logger);
        $this->assertFileExists($destinationFile1);
        $this->assertFileExists($destinationFile2);
        $this->assertFileExists($destinationFile3);

        $this->assertFileNotExists($destinationFile4);
        $destination->push($file4, $logger);
        $this->assertFileNotExists($destinationFile1);
        $this->assertFileExists($destinationFile2);
        $this->assertFileExists($destinationFile3);
        $this->assertFileExists($destinationFile4);

        $this->assertFileNotExists($destinationFile5);
        $destination->push($file5, $logger);
        $this->assertFileNotExists($destinationFile2);
        $this->assertFileExists($destinationFile3);
        $this->assertFileExists($destinationFile4);
        $this->assertFileExists($destinationFile5);

        $this->tearDown();
    }
}