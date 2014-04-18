<?php

namespace Zenstruck\BackupBundle\Tests\Fixtures;

use Psr\Log\LoggerInterface;
use Zenstruck\BackupBundle\Namer\Namer;
use Zenstruck\BackupBundle\Processor\Processor;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class NullProcessor implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function process($scratchDir, Namer $namer, LoggerInterface $logger)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function cleanup($filename, LoggerInterface $logger)
    {
        // noop
    }
}
