<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class S3CmdDestination implements Destination
{
    const DEFAULT_TIMEOUT = 300;

    private $bucket;
    private $timeout;

    /**
     * @param string $bucket
     * @param int    $timeout The process timeout in seconds
     */
    public function __construct($bucket, $timeout = self::DEFAULT_TIMEOUT)
    {
        $this->bucket = $bucket;
        $this->timeout = $timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function push($filename, LoggerInterface $logger)
    {
        $destination = sprintf('%s/%s', $this->bucket, basename($filename));

        $logger->info(sprintf('Uploading %s to: %s', $filename, $destination));

        $process = ProcessBuilder::create(array('s3cmd', 'put', $filename, $destination))->getProcess();
        $process->setTimeout($this->timeout);

        $process->run(
            function ($type, $buffer) use ($logger) {
                $logger->debug($buffer);
            }
        );

        if (!$process->isSuccessful() || false !== strpos($process->getErrorOutput(), 'ERROR:')) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }
}
