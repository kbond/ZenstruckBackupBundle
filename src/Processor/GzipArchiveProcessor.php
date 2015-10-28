<?php

namespace Zenstruck\BackupBundle\Processor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GzipArchiveProcessor extends ArchiveProcessor
{
    const DEFAULT_OPTIONS = '-czvf';

    public function __construct($options = self::DEFAULT_OPTIONS)
    {
        parent::__construct('tar', $options, 'tar.gz');
    }
}
