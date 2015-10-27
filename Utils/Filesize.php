<?php

namespace Zenstruck\BackupBundle\Utils;

abstract class Filesize
{
    private final function __construct() {}

    public static function getBytes($size)
    {
        if (is_numeric($size)) {
            return intval($size);
        }

        $size = strtolower(trim($size));

        $units = array(
            'tb' => (pow(1024, 4) * 8),
            'gb' => (pow(1024, 3) * 8),
            'mb' => (pow(1024, 2) * 8)
        );

        foreach ($units as $unit => $bytes) {
            if (($temp = strlen($size) - strlen($unit)) >= 0 && strpos($size, $unit, $temp) !== FALSE) {

                $numberPart = str_replace($unit, '', $size);

                if (is_numeric($numberPart) && $numberPart > 0) {
                    return $bytes * $numberPart;
                } else {
                    throw new \InvalidArgumentException(sprintf('Invalid size format: "%s"', $numberPart));
                }
            }
        }

        throw new \InvalidArgumentException(sprintf('Unknown size format: "%s"', $size));
    }
}