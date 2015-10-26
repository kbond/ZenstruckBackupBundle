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
    private $timezone;

    public function __construct($format = self::DEFAULT_FORMAT, $prefix = self::DEFAULT_PREFIX, $timezone = null)
    {
        $this->format = $format;
        $this->prefix = $prefix;
        $this->timezone = $timezone ? new \DateTimeZone($timezone) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $dateTime = new \DateTime('now', $this->timezone);

        return $this->prefix.$dateTime->format($this->format);
    }
}
