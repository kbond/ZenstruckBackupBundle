<?php

namespace Zenstruck\BackupBundle\Tests;

use Symfony\Component\Filesystem\Filesystem;

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
