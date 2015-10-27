<?php

namespace Zenstruck\BackupBundle\Tests\Fixtures;

use Psr\Log\LoggerInterface;
use Zenstruck\BackupBundle\Destination\Backup;
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

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Backup $backup, LoggerInterface $logger)
    {
        throw new \RuntimeException('There are no backups to remove from NullDestination.');
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        throw new \RuntimeException('There are no backups to fetch in NullDestination.');
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return false;
    }
}
