<?php

namespace Zenstruck\BackupBundle\Rotator;

use Zenstruck\BackupBundle\Destination\Backup;

final class MaxStorageSizeRotator implements Rotator
{
    /**
     * @var integer
     */
    private $maxSize;

    /**
     * A constructor.
     *
     * @param integer $maxSize Maximum storage size.
     */
    public function __construct($maxSize)
    {
        $this->maxSize = $maxSize;
    }

    /**
     * {@inheritdoc}
     */
    public function nominate(array $backups)
    {
        $list = [];
        $currentSize = 0;

        /**
         * @var Backup $backup
         */
        foreach ($backups as $backup) {
            $list[$backup->getCreatedAt()->getTimestamp()] = $backup;
            $currentSize += $backup->getSize();
        }

        if ($currentSize > $this->maxSize) {

            ksort($list);

            $nominations = [];

            /**
             * @var Backup $backup
             */
            foreach ($list as $backup) {

                $nominations[] = $backup;
                $currentSize -= $backup->getSize();

                if ($currentSize <= $this->maxSize) {
                    break;
                }
            }

            return $nominations;

        } else {
            return [];
        }
    }
}