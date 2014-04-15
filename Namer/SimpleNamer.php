<?php

namespace Zenstruck\BackupBundle\Namer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SimpleNamer implements Namer
{
    const DEFAULT_NAME = 'backup';

    private $name;

    /**
     * @param string $name
     */
    public function __construct($name = self::DEFAULT_NAME)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
}
