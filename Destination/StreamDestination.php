<?php

namespace Zenstruck\BackupBundle\Destination;

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


        if (!$this->backups) {
            $this->getBackups();
        }
        $backup = Backup::fromFile($destination);
        $this->backups[$backup->getKey()] = $backup;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getBackups());
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->getBackups());
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Backup $backup, LoggerInterface $logger)
    {
        @unlink($backup->getFilename());

        if ($this->backups) {
            $this->backups[$backup->getKey()];
        }

        $logger->info(sprintf('Backup "%s" removed.', $backup->getFilename()));
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return $this->backups[$key];
        }

        throw new \RuntimeException(sprintf('Backup with given key "%s" does not exists.', $key));
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return array_key_exists($key, $this->getBackups());
    }

    /**
     * Get backups.
     *
     * Get backups. If backups are not initialized, lazy load them.
     *
     * @return Backup[]
     */
    protected function getBackups()
    {
        if (!$this->backups) {

            $list = glob(sprintf('%s/*.*', $this->directory));

            $this->backups = array();

            foreach ($list as $file) {
                if (is_file($file)) {
                    $backup = Backup::fromFile($file);
                    $this->backups[$backup->getKey()] = $backup;
                }
            }
        }


        return $this->backups;
    }
}
