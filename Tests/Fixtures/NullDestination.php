<?php

namespace Zenstruck\BackupBundle\Tests\Fixtures;

use Psr\Log\LoggerInterface;
use Zenstruck\BackupBundle\Destination\Destination;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class NullDestination implements Destination
{
    /**
     * {@inheritdoc}
     */
    public function push($filename, LoggerInterface $logger)
    {
        // noop
    }
}
