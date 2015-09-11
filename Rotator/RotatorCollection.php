<?php

namespace Zenstruck\BackupBundle\Rotator;

use ArrayIterator;
use Countable;
use IteratorAggregate;

final class RotatorCollection implements Countable, IteratorAggregate, Rotator
{
    private $rotators;

    public function __construct(array $rotators = array())
    {
        $this->rotators = $rotators;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->rotators);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->rotators);
    }

    /**
     * Check if collection has rotators.
     *
     * @return bool
     */
    public function hasRotators()
    {
        return $this->count() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function nominate(array $backups)
    {
        $result = array();

        /** @var Rotator $rotator */
        foreach ($this->rotators as $rotator) {
            foreach ($nominations = $rotator->nominate($backups) as $nomination) {
                if (!in_array($nomination, $result)) {
                    $result[] = $nomination;
                }
            }
        }

        return $result;
    }
}