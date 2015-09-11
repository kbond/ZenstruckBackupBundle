<?php

namespace Zenstruck\BackupBundle\Destination;
use DateTime;

/**
 * Class Backup
 *
 * Abstraction of one backup file.
 *
 * @package Zenstruck\BackupBundle\Destination
 */
final class Backup
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var integer
     */
    private $size;

    /**
     * @var \DateTimeInterface
     */
    private $createdAt;

    public function __construct($key, $filename, $size, $createdAt)
    {
        $this->key = $key;
        $this->filename = $filename;
        $this->size = $size;
        $this->createdAt = is_integer($createdAt) ? date_timestamp_set(new DateTime(), $createdAt) : $createdAt;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Builds backup from file.
     *
     * @param string $path Path to file.
     * @return Backup Backup file.
     */
    public static function fromFile($path)
    {
        return new Backup($path, $path, filesize($path), filemtime($path));
    }
}