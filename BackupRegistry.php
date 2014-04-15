<?php

namespace Zenstruck\BackupBundle;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class BackupRegistry
{
    /** @var BackupManager[] */
    private $managers = array();

    /**
     * @param string        $name
     * @param BackupManager $manager
     */
    public function add($name, BackupManager $manager)
    {
        $this->managers[$name] = $manager;
    }

    /**
     * @param string $name
     *
     * @return BackupManager
     *
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if (!isset($this->managers[$name])) {
            throw new \InvalidArgumentException(sprintf('Profile "%s" does not exist.', $name));
        }

        return $this->managers[$name];
    }
}
