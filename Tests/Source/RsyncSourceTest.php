<?php

namespace Zenstruck\BackupBundle\Tests\Source;

use Psr\Log\NullLogger;
use Zenstruck\BackupBundle\Source\RsyncSource;
use Zenstruck\BackupBundle\Tests\BaseTest;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RsyncSourceTest extends BaseTest
{
    public function testFetch()
    {
        $scratch = $this->getScratchDir();
        $source = new RsyncSource($this->getFixtureDir());

        $this->assertFileNotExists($scratch.'/Fixtures/foo.txt');

        $source->fetch($scratch, new NullLogger());

        $this->assertFileExists($scratch.'/Fixtures/foo.txt');
    }

    public function testInvalidSource()
    {
        $this->setExpectedException('\RuntimeException');

        $scratch = $this->getScratchDir();
        $source = new RsyncSource('/foo/bar');

        $source->fetch($scratch, new NullLogger());
    }
}
