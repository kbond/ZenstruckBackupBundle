<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Destination
{
    /**
     * @param string          $filename
     * @param LoggerInterface $logger
     */
    public function push($filename, LoggerInterface $logger);
}
