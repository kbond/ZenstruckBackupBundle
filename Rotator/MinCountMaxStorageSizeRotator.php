<?php

namespace Zenstruck\BackupBundle\Rotator;

use Zenstruck\BackupBundle\Destination\Backup;
use Zenstruck\BackupBundle\Utils\Filesize;

final class MinCountMaxStorageSizeRotator
{
    /**
     * @var integer
     */
    private $maxSize;

    /**
     * @var integer
     */
    private $minCount;

    /**
     * A constructor.
     *
     * @param integer $minCount Minimum backups to keep.
     * @param integer $maxSize Maximum storage size.
     */
    public function __construct($minCount, $maxSize)
    {
        if ($minCount < 1) {
            throw new \InvalidArgumentException('At least one file should be set as minimum backup count.');
        }

        $this->minCount = $minCount;

        if (is_integer($maxSize)) {
            $this->maxSize = $maxSize;
        } else {
            if (class_exists('\\ByteUnits\\System')) {
                $this->maxSize = \ByteUnits\parse($maxSize)->format('B') * 8;
            } else {
                $this->maxSize = Filesize::getBytes($maxSize);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function nominate(array $backups)
    {
        $list = array();
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

            $nominations = array();

            /**
             * @var Backup $backup
             */
            foreach ($list as $backup) {

                if (count($list) - count($nominations) <= $this->minCount) {
                    break;
                }

                $nominations[] = $backup;
                $currentSize -= $backup->getSize();

                if ($currentSize <= $this->maxSize) {
                    break;
                }
            }

            return $nominations;

        } else {
            return array();
        }
    }
}