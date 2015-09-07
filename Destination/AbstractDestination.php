<?php

namespace Zenstruck\BackupBundle\Destination;

use Psr\Log\LoggerInterface;
use Zenstruck\BackupBundle\Rotator\Rotator;

abstract class AbstractDestination implements Destination
{
    /**
     * @var array<Backup>
     */
    protected $backups;

    /**
     * @var array<Rotator>
     */
    protected $preRotators;

    /**
     * @var array<Rotator>
     */
    protected $postRotators;

    /**
     * A constructor.
     *
     * @param array<Rotator> $preRotators Rotators that nominate backups for rotation prior to push.
     * @param array<Rotator> $postRotators Rotators that nominate backups for rotation after push.
     */
    public function __construct(array $preRotators = [], array $postRotators = [])
    {
        $this->preRotators = $preRotators;
        $this->postRotators = $postRotators;
    }

    /**
     * {@inheritdoc}
     */
    public function push($filename, LoggerInterface $logger)
    {
        /** @var Backup $rotatingBackup */
        foreach (
            $rotations = $this->getRotations(
                $this->preRotators,
                array_merge(
                    $this->getBackups(),
                    [new Backup($filename, $filename, filesize($filename), mktime())]
                )
            ) as $rotatingBackup
        ) {
            $logger->info(sprintf('Freeing destination from backup "%s" prior to push.', $rotatingBackup->getFilename()));
            $this->remove($rotatingBackup, $logger);
        }

        $this->doPush($filename, $logger);

        foreach ($rotations = $this->getRotations($this->postRotators, $this->backups) as $rotatingBackup) {
            $logger->info(sprintf('Freeing destination from backup "%s" after push.', $rotatingBackup->getFilename()));
            $this->remove($rotatingBackup, $logger);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(Backup $backup, LoggerInterface $logger)
    {
        $this->doRemove($backup, $logger);

        if (!$this->backups) {
            $this->doLoadBackups();
        } else {
            $this->backups = array_filter($this->backups, function(Backup $current) use ($backup) {
                return $current->getKey() != $backup->getKey();
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if (!$this->backups) {
            $this->doLoadBackups();
        }

        return $this->backups;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if (!$this->backups) {
            $this->doLoadBackups();
        }

        return count($this->backups);
    }

    /**
     * Lazy load list of backups.
     */
    protected abstract function doLoadBackups();

    /**
     * Push file to destination.
     *
     * @param string $filename Path to file to push to destination.
     * @param LoggerInterface $logger Logger to use for logging.
     * @return Backup Pushed backup file.
     */
    protected abstract function doPush($filename, LoggerInterface $logger);

    /**
     * Remove backup from destination.
     *
     * @param Backup $backup Backup to remove from destintion.
     * @param LoggerInterface $logger Logger to use for logging.
     */
    protected abstract function doRemove(Backup $backup, LoggerInterface $logger);

    /**
     * Get list of backups.
     *
     * Get list of backups on current destination.
     * If list is not loaded, load it first.
     *
     * @return array<Backup>
     */
    private function getBackups()
    {
        if (!$this->backups) {
            $this->doLoadBackups();
        }

        return $this->backups;
    }

    /**
     * Cross-check backups with rotators and return backup files for rotation.
     *
     * @param array<Rotator> $rotators Rotators to execute.
     * @param array<Backup> $backups Backup files to check agains rotation condition.
     * @return array<Backup> Backups to remove.
     */
    private function getRotations(array $rotators, array $backups)
    {
        $list = [];

        /**
         * @var Rotator $rotator
         */
        foreach ($rotators as $rotator) {

            $nominations = $rotator->nominate($backups);

            foreach ($nominations as $nomination) {
                if (!in_array($nomination, $list)) {
                    $list[] = $nomination;
                }
            }
        }

        return $list;
    }
}