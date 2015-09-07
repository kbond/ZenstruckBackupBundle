<?php

namespace Zenstruck\BackupBundle\Rotator;

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
        $this->count = $count;
    }

    /**
     * {@inheritdoc}
     */
    public function nominate(array $backups)
    {
        if ($currentCount = count($backups) > $this->count) {

            $list = [];

            /**
             * @var Backup $backup
             */
            foreach ($backups as $backup) {
                $list[$backup->getCreatedAt()->getTimestamp()] = $backup;
            }

            ksort($list);

            $nominations = [];

            /**
             * @var Backup $backup
             */
            foreach ($list as $backup) {
                $nominations[] = $backup;

                if ($currentCount <= $this->count) {
                    break;
                }
            }

            return $nominations;

        } else {
            return [];
        }
    }
}