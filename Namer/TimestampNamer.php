<?php

namespace Zenstruck\BackupBundle\Namer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TimestampNamer implements Namer
{
    const DEFAULT_FORMAT = 'YmdHis';
    const DEFAULT_PREFIX = '';

    private $format;
    private $prefix;

    public function __construct($format = self::DEFAULT_FORMAT, $prefix = self::DEFAULT_PREFIX)
    {
        $this->format = $format;
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $dateTime = new \DateTime();

        return $this->prefix.$dateTime->format($this->format);
    }
}
