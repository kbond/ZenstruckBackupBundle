<?php

namespace Zenstruck\BackupBundle\Processor;

use Psr\Log\LoggerInterface;
use Zenstruck\BackupBundle\Namer\Namer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Processor
{
    /**
     * @param string          $scratchDir Path to the scratch directory
     * @param Namer           $namer
     * @param LoggerInterface $logger
     *
     * @return string The filename to backup
     */
    public function process($scratchDir, Namer $namer, LoggerInterface $logger);

    /**
     * @param string          $filename The file to cleanup
     * @param LoggerInterface $logger
     */
    public function cleanup($filename, LoggerInterface $logger);
}
