<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class S3CmdDestination extends AbstractDestination
{
    const DEFAULT_TIMEOUT = 300;

    private $bucket;
    private $timeout;
    private $options;

    /**
     * @param string $bucket
     * @param int    $timeout The process timeout in seconds
     * @param array  $options s3cmd command options
     */
    public function __construct($bucket, $timeout = self::DEFAULT_TIMEOUT, array $options = array(), array $preRotators = [], array $postRotators = [])
    {
        parent::__construct($preRotators, $postRotators);
        $this->bucket = $bucket;
        $this->timeout = $timeout;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function doPush($filename, LoggerInterface $logger)
    {
        $destination = sprintf('%s/%s', $this->bucket, basename($filename));

        $logger->info(sprintf('Uploading %s to: %s', $filename, $destination));

        $process = ProcessBuilder::create($this->options)
            ->setPrefix(array('s3cmd', 'put'))
            ->add($filename)
            ->add($destination)
            ->setTimeout($this->timeout)
            ->getProcess();

        $process->run(
            function ($type, $buffer) use ($logger) {
                $logger->debug($buffer);
            }
        );

        if (!$process->isSuccessful() || false !== strpos($process->getErrorOutput(), 'ERROR:')) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function doLoadBackups()
    {
        // TODO: Implement doLoadBackups() method.
        throw new \RuntimeException('Not implemented yet.');
    }

    /**
     * {@inheritdoc}
     */
    public function doRemove(Backup $backup, LoggerInterface $logger)
    {
        // TODO: Implement remove() method.
        throw new \RuntimeException('Not implemented yet.');
    }
}
