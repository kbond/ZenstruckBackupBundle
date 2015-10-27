<?php

namespace Zenstruck\BackupBundle\Tests;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Filesystem\Filesystem;
use Zenstruck\BackupBundle\BackupManager;
use Zenstruck\BackupBundle\Namer\SimpleNamer;
use Zenstruck\BackupBundle\Tests\Fixtures\NullDestination;
use Zenstruck\BackupBundle\Tests\Fixtures\NullProcessor;
use Zenstruck\BackupBundle\Tests\Fixtures\NullSource;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->removeScratchDir();

        mkdir($this->getScratchDir());
    }

    protected function tearDown()
    {
        $this->removeScratchDir();
    }

    protected function createNullBackupManager(LoggerInterface $logger = null)
    {
        if (!$logger) {
            $logger = new NullLogger();
        }

        return new BackupManager(
            $this->getScratchDir(),
            new NullProcessor(),
            new SimpleNamer(),
            array(new NullSource()),
            new NullDestination(),
            $logger
        );
    }

    protected function getFixtureDir()
    {
        return __DIR__.'/Fixtures';
    }

    protected function getScratchDir()
    {
        return sys_get_temp_dir().'/zenstruck-backup-bundle';
    }

    private function removeScratchDir()
    {
        $dir = $this->getScratchDir();

        if (file_exists($dir)) {
            $filesystem = new Filesystem();
            $filesystem->remove($dir);
        }
    }
}
