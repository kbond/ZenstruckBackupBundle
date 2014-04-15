<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class StreamDestination implements Destination
{
    private $directory;

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

        copy($filename, sprintf('%s/%s', $this->directory, basename($filename)));
    }
}
