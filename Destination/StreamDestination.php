<?php

namespace Zenstruck\BackupBundle\Destination;

use ArrayIterator;
use Psr\Log\LoggerInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class StreamDestination implements Destination
{
    /**
     * @var string Path to directory.
     */
    private $directory;

    /**
     * @var array<Backup> List of backups.
     */
    protected $backups;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function push($filename, LoggerInterface $logger)
    {
        $logger->info(sprintf('Copying %s to %s', $filename, $this->directory));

        copy($filename, ($destination = sprintf('%s/%s', $this->directory, basename($filename))));
        touch($destination, filemtime($filename));

        if (is_array($this->backups)) {
            $this->backups[] = Backup::fromFile($destination);
        } else {
            $this->lazyLoadBackups();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if (!is_array($this->backups)) {
            $this->lazyLoadBackups();
        }

        return new ArrayIterator($this->backups);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if (!is_array($this->backups)) {
            $this->lazyLoadBackups();
        }

        return count($this->backups);
    }

    /**
     * Loads list of backups in lazy manner.
     */
    protected function lazyLoadBackups()
    {
        $list = glob(sprintf('%s/*.*', $this->directory));

        $this->backups = array();

        foreach ($list as $file) {
            if (is_file($file)) {
                $this->backups[] = Backup::fromFile($file);
            }
        }
    }
}
