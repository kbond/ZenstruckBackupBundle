<?php

namespace Zenstruck\BackupBundle\Tests\Processor;

use Zenstruck\BackupBundle\Processor\GzipArchiveProcessor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GzipArchiveProcessorTest extends ArchiveProcessorTest
{
    /**
     * {@inheritdoc}
     */
    protected function getProcessor()
    {
        return new GzipArchiveProcessor();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return 'tar.gz';
    }
}
