<?php

namespace Zenstruck\BackupBundle\Tests\Fixtures;

use Psr\Log\LoggerInterface;
use Zenstruck\BackupBundle\Source\Source;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class NullSource implements Source
{
    /**
     * {@inheritdoc}
     */
    public function fetch($scratchDir, LoggerInterface $logger)
    {
        // noop
    }
}
