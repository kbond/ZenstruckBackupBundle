<?php

namespace Zenstruck\BackupBundle\Source;

use Psr\Log\LoggerInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Source
{
    /**
     * @param string          $scratchDir Path to the scratch directory
     * @param LoggerInterface $logger
     */
    public function fetch($scratchDir, LoggerInterface $logger);
}
