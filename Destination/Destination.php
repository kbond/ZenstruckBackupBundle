<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Destination extends \Countable, \IteratorAggregate
{
    /**
     * Push backup to destination.
     *
     * @param string          $filename Path to backup file to push.
     * @param LoggerInterface $logger A logger.
     */
    public function push($filename, LoggerInterface $logger);

    /**
     * Removes backup from destination.
     *
     * @param Backup $backup
     * @param LoggerInterface $logger A logger.
     */
    public function remove(Backup $backup, LoggerInterface $logger);

    /**
     * Get backup with given key.
     *
     * @param string $key Backup key.
     * @return Backup Resulting backup.
     * @throws \RuntimeException If backup with given key does not exists.
     */
    public function get($key);

    /**
     * Check if destination has backup with given key.
     *
     * @param string $key Backup key.
     * @return boolean TRUE if backup exist.
     */
    public function has($key);
}
