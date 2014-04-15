<?php

namespace Zenstruck\BackupBundle\Tests\Processor;

use Zenstruck\BackupBundle\Processor\ZipArchiveProcessor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZipArchiveProcessorTest extends ArchiveProcessorTest
{
    /**
     * {@inheritdoc}
     */
    protected function getProcessor()
    {
        return new ZipArchiveProcessor();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtension()
    {
        return 'zip';
    }
}
