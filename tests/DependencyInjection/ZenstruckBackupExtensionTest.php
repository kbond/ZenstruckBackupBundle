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
    /**
     * @test
     */
    public function can_compile_with_no_config()
    {
        $this->load();
        $this->compile();

        $this->assertContainerBuilderHasService('zenstruck_backup.profile_registry');
        $this->assertContainerBuilderHasService('zenstruck_backup.profile_builder');
        $this->assertContainerBuilderHasService('zenstruck_backup.executor');
    }

    /**
     * @test
     */
    public function compile_with_valid_config()
    {
        $this->load($this->loadConfig('valid_config.yml'));
        $this->compile();

        $this->assertContainerBuilderHasService('zenstruck_backup.source.database');
        $this->assertContainerBuilderHasService('zenstruck_backup.source.files');
        $this->assertContainerBuilderHasService('zenstruck_backup.namer.simple');
        $this->assertContainerBuilderHasService('zenstruck_backup.namer.daily');
        $this->assertContainerBuilderHasService('zenstruck_backup.namer.snapshot');
        $this->assertContainerBuilderHasService('zenstruck_backup.processor.zip');
        $this->assertContainerBuilderHasService('zenstruck_backup.processor.gzip');
        $this->assertContainerBuilderHasService('zenstruck_backup.destination.s3');
        $this->assertContainerBuilderHasService('zenstruck_backup.destination.stream');
        $this->assertContainerBuilderHasService('zenstruck_backup.profile.daily');
        $this->assertContainerBuilderHasService('zenstruck_backup.profile.monthly');
    }

    /**
     * @test
     *
     * @dataProvider invalidConfigProvider
     */
    public function compile_with_invalid_config($file, $message, $expectedException)
    {
        $this->setExpectedException($expectedException, $message);

        $this->load($this->loadConfig($file));
        $this->compile();
    }

    public static function invalidConfigProvider()
    {
        return array(
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
