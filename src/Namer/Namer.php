<?php

namespace Zenstruck\BackupBundle\Namer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface Namer
{
    /**
     * @return string
     */
    public function getName();
}
