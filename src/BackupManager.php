<?php

namespace Zenstruck\BackupBundle;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Zenstruck\BackupBundle\Destination\Destination;
use Zenstruck\BackupBundle\Namer\Namer;
use Zenstruck\BackupBundle\Processor\Processor;
use Zenstruck\BackupBundle\Source\Source;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupManager
{
    private $scratchDir;
    private $sources;
    private $processor;
    private $namer;
    private $destinations;
    private $logger;

    /**
     * @param string          $scratchDir
     * @param Processor       $processor
     * @param Namer           $namer
     * @param Source[]        $sources
     * @param Destination[]   $destinations
     * @param LoggerInterface $logger
     */
    public function __construct($scratchDir, Processor $processor, Namer $namer, array $sources, array $destinations, LoggerInterface $logger)
    {
        $this->scratchDir = $scratchDir;
        $this->processor = $processor;
        $this->namer = $namer;
        $this->sources = $sources;
        $this->destinations = $destinations;
        $this->logger = $logger;
    }

    public function backup($clear = false)
    {
        if ($clear) {
            $this->logger->info('Clearing scratch directory...');
            $filesystem = new Filesystem();
            $filesystem->remove($this->scratchDir);
        }

        if (!is_dir($this->scratchDir)) {
            mkdir($this->scratchDir, 0777, true);
        }

        $this->logger->info('Beginning backup...');

        foreach ($this->sources as $source) {
            $source->fetch($this->scratchDir, $this->logger);
        }

        $filename = $this->processor->process($this->scratchDir, $this->namer, $this->logger);

        try {
            $this->sendToDestinations($filename);
        } catch (\Exception $e) {
            $this->processor->cleanup($filename, $this->logger);

            throw $e;
        }

        $this->processor->cleanup($filename, $this->logger);
        $this->logger->info('Done.');
    }

    private function sendToDestinations($filename)
    {
        foreach ($this->destinations as $destination) {
            $destination->push($filename, $this->logger);
        }
    }
}
