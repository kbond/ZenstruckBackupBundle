<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class StreamDestination extends AbstractDestination
{
    private $directory;

    public function __construct($directory, array $preRotators = [], array $postRotators = [])
    {
        parent::__construct($preRotators, $postRotators);
        $this->directory = $directory;
    }

    /**
     * {@inheritdoc}
     */
    protected function doPush($filename, LoggerInterface $logger)
    {
        $logger->info(sprintf('Copying %s to %s', $filename, $this->directory));

        copy($filename, $target = sprintf('%s/%s', $this->directory, basename($filename)));

        return new Backup($target, $target, filesize($target), filemtime($target));
    }

    /**
     * {@inheritdoc}
     */
    public function doRemove(Backup $backup, LoggerInterface $logger)
    {
        unlink($backup->getKey());
    }

    /**
     * {@inheritdoc}
     */
    protected function doLoadBackups()
    {
        $list = glob(sprintf('%s/*.*', $this->directory));

        $this->backups = [];

        foreach ($list as $file) {
            if (is_file($file)) {
                $this->backups[] = new Backup($file, $file, filesize($file), filemtime($file));
            }
        }
    }
}
