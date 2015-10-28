<?php

namespace Zenstruck\BackupBundle\Tests\Processor;

use Psr\Log\NullLogger;
use Zenstruck\BackupBundle\Namer\SimpleNamer;
use Zenstruck\BackupBundle\Processor\ArchiveProcessor;
use Zenstruck\BackupBundle\Tests\BaseTest;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class ArchiveProcessorTest extends BaseTest
{
    public function testProcessAndClean()
    {
        $processor = $this->getProcessor();
        $logger = new NullLogger();

        $this->assertFileNotExists($this->getTempFilename());

        $filename = $processor->process($this->getFixtureDir(), new SimpleNamer('zenstruck-backup-foo'), $logger);

        $this->assertFileExists($filename);
        $this->assertSame($filename, $this->getTempFilename());

        $processor->cleanup($filename, $logger);

        $this->assertFileNotExists($filename);
    }

    /**
     * @return ArchiveProcessor
     */
    abstract protected function getProcessor();

    /**
     * @return string
     */
    abstract protected function getExtension();

    protected function setUp()
    {
        parent::setUp();

        $this->deleteTempFile();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->deleteTempFile();
    }

    private function deleteTempFile()
    {
        $filename = $this->getTempFilename();

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    private function getTempFilename()
    {
        return sys_get_temp_dir().'/zenstruck-backup-foo.'.$this->getExtension();
    }
}
