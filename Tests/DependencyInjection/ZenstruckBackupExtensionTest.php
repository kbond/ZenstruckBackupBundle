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
        $this->load($this->loadConfig('full_config.yml'));
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

    /**
     * @dataProvider invalidConfigProvider
     */
    public function testInvalidConfig($file, $message)
    {
        $this->setExpectedException('\LogicException', $message);

        $this->load($this->loadConfig($file));
        $this->compile();
    }

    public function invalidConfigProvider()
    {
        return array(
            array('invalid_source.yml', 'Source "foo" is not defined.'),
            array('invalid_namer.yml', 'Namer "foo" is not defined.'),
            array('invalid_destination.yml', 'Destination "foo" is not defined.'),
            array('invalid_processor.yml', 'Processor "foo" is not defined.'),
        );
    }

    protected function getContainerExtensions()
    {
        return array(new ZenstruckBackupExtension());
    }

    private function loadConfig($file)
    {
        return Yaml::parse(file_get_contents(__DIR__.'/../Fixtures/'.$file));
    }
}
