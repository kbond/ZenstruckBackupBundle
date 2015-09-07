<?php

namespace Zenstruck\BackupBundle\Rotator;

interface Rotator
{
    /**
     * Determine which backups from the list should be deleted.
     *
     * @param array<Backup> $backups
     * @return mixed
     */
    public function nominate(array $backups);
}