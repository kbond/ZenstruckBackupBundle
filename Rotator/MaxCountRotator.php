<?php

namespace Zenstruck\BackupBundle\Rotator;

use InvalidArgumentException;
use Zenstruck\BackupBundle\Destination\Backup;

final class MaxCountRotator implements Rotator
{
    /**
     * @var integer
     */
    private $count;

    /**
     * A constructor.
     *
     * @param integer $count Maximum nb of backups.
     */
    public function __construct($count)
    {
        if ($count < 1) {
            throw new InvalidArgumentException('You need to allow at least one backup file to be created.');
        }
        $this->count = $count;
    }

    /**
     * {@inheritdoc}
     */
    public function nominate(array $backups)
    {
        if (($currentCount = count($backups)) > $this->count) {

            $list = array();

            /**
             * @var Backup $backup
             */
            foreach ($backups as $backup) {
                $list[$backup->getCreatedAt()->getTimestamp()] = $backup;
            }

            ksort($list);

            $nominations = array();

            /**
             * @var Backup $backup
             */
            foreach ($list as $backup) {
                $nominations[] = $backup;
                $currentCount--;

                if ($currentCount <= $this->count) {
                    break;
                }
            }

            return $nominations;

        } else {
            return array();
        }
    }
}