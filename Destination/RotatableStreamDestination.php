<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;
use Zenstruck\BackupBundle\Rotator\Rotator;

class RotatableStreamDestination extends StreamDestination
{
    /**
     * @var null|Rotator
     */
    private $preRotator;

    /**
     * @var null|Rotator
     */
    private $postRotator;

    /**
     * A constructor
     *
     * @param string $directory Path to backup directory.
     * @param Rotator|null $preRotator Rotator to execute prior to push.
     * @param Rotator|null $postRotator Rotator to execute after push.
     */
    public function __construct($directory, Rotator $preRotator = null, Rotator $postRotator = null)
    {
        parent::__construct($directory);
        $this->preRotator = $preRotator;
        $this->postRotator = $postRotator;
    }

    /**
     * {@inheritdoc}
     */
    public function push($filename, LoggerInterface $logger)
    {
        if ($this->preRotator) {

            if (!is_array($this->backups)) {
                $this->lazyLoadBackups();
            }

            $nominations = $this->preRotator->nominate(array_merge($this->backups, array(Backup::fromFile($filename))));

            foreach ($nominations as $nomination) {
                $this->remove($nomination, $logger);
            }
        }

        parent::push($filename, $logger);

        if ($this->postRotator) {

            $nominations = $this->postRotator->nominate($this->backups);

            foreach ($nominations as $nomination) {
                $this->remove($nomination, $logger);
            }
        }
    }

    /**
     * Remove backup from backup location.
     *
     * @param Backup $backup Backup to remove.
     * @param LoggerInterface $logger A logger.
     */
    private function remove(Backup $backup, LoggerInterface $logger)
    {
        unlink($backup->getKey());
        $logger->info(sprintf('Backup "%s" deleted due to rotation.', $backup->getFilename()));

        if ($this->backups) {
            $this->backups = array_filter($this->backups, function($current) use ($backup) {
                return $current != $backup;
            });
        }
    }
}
