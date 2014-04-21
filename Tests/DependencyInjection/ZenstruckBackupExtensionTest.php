<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\Yaml\Yaml;
use Zenstruck\BackupBundle\DependencyInjection\ZenstruckBackupExtension;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckBackupExtensionTest extends AbstractExtensionTestCase
{
    public function testDefault()
    {
        $this->load();
        $this->compile();

        $this->assertTrue($this->container->has('zenstruck_backup.command'));
        $this->assertTrue($this->container->has('zenstruck_backup.registry'));
    }

    public function testConfig()
    {
        $config = Yaml::parse(__DIR__.'/../Fixtures/full_config.yml');

        $this->load($config);
        $this->compile();

        $this->assertTrue($this->container->has('zenstruck_backup.source.database'));
        $this->assertTrue($this->container->has('zenstruck_backup.source.files'));
        $this->assertTrue($this->container->has('zenstruck_backup.namer.simple'));
        $this->assertTrue($this->container->has('zenstruck_backup.namer.daily'));
        $this->assertTrue($this->container->has('zenstruck_backup.namer.snapshot'));
        $this->assertTrue($this->container->has('zenstruck_backup.processor.zip'));
        $this->assertTrue($this->container->has('zenstruck_backup.processor.gzip'));
        $this->assertTrue($this->container->has('zenstruck_backup.destination.s3'));
        $this->assertTrue($this->container->has('zenstruck_backup.destination.stream'));
        $this->assertTrue($this->container->has('zenstruck_backup.manager.daily'));
    }

    protected function getContainerExtensions()
    {
        return array(new ZenstruckBackupExtension());
    }
}
