<?php

namespace Zenstruck\BackupBundle\Processor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZipArchiveProcessor extends ArchiveProcessor
{
    const DEFAULT_OPTIONS = '-r';

    public function __construct($options = self::DEFAULT_OPTIONS)
    {
        parent::__construct('zip', $options, 'zip');
    }
}
