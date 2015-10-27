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
        $this->assertTrue($this->container->has('zenstruck_backup.manager.monthly'));
    }

    /**
     * @dataProvider invalidConfigProvider
     */
    public function testInvalidConfig($file, $message, $expectedException = '\LogicException')
    {
        $this->setExpectedException($expectedException, $message);

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
            array('invalid_profile_missing_sources.yml', 'The child node "sources" at path "zenstruck_backup.profiles.daily" must be configured.', 'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'),
            array('invalid_profile_missing_namer.yml', 'The child node "namer" at path "zenstruck_backup.profiles.daily" must be configured.', 'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'),
            array('invalid_profile_missing_destinations.yml', 'The child node "destinations" at path "zenstruck_backup.profiles.daily" must be configured.', 'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'),
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
