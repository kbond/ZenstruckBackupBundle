<?php

namespace Zenstruck\BackupBundle\Tests\Destination;

use Psr\Log\NullLogger;
use Zenstruck\BackupBundle\Destination\StreamDestination;
use Zenstruck\BackupBundle\Tests\BaseTest;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class StreamDestinationTest extends BaseTest
{
    public function testPush()
    {
        $file = $this->getFixtureDir().'/foo.txt';
        $destinationDir = $this->getScratchDir();
        $destinationFile = $destinationDir.'/foo.txt';
        $destination = new StreamDestination($destinationDir);

        $this->assertFileNotExists($destinationFile);

        $destination->push($file, new NullLogger());

        $this->assertFileExists($destinationFile);
    }
}
