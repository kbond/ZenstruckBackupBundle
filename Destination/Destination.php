<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;
use \Countable;
use \IteratorAggregate;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Destination extends Countable, IteratorAggregate
{
    /**
     * @param string          $filename
     * @param LoggerInterface $logger
     */
    public function push($filename, LoggerInterface $logger);
}
