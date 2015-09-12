<?php

namespace Zenstruck\BackupBundle\Rotator;

use Zenstruck\BackupBundle\Destination\Backup;

interface Rotator
{
    /**
     * Determine which backups from the list should be deleted.
     *
     * @param Backup[] $backups Backups from which nomination should be made.
     * @return Backup[] Nominations of backups for rotation.
     */
    public function nominate(array $backups);
}