<?php

namespace Zenstruck\BackupBundle\Rotator;

use Zenstruck\BackupBundle\Destination\Backup;
use Zenstruck\BackupBundle\Utils\Filesize;

final class MaxStorageSizeRotator implements Rotator
{
    /**
     * @var integer
     */
    private $maxSize;

    /**
     * A constructor.
     *
     * @param integer|string $maxSize Maximum storage size.
     */
    public function __construct($maxSize)
    {
        if (is_numeric($maxSize)) {
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