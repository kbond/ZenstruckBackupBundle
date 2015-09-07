<?php

namespace Zenstruck\BackupBundle\Destination;

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
        $this->createdAt = $createdAt;
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
}