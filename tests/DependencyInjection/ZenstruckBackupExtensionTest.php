<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\Yaml\Yaml;
use Zenstruck\BackupBundle\Command\ListCommand;
use Zenstruck\BackupBundle\Command\RunCommand;
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
        $this->assertContainerBuilderHasService('zenstruck_backup.command.list', ListCommand::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.command.list', 'console.command');
        $this->assertContainerBuilderHasService('zenstruck_backup.command.run', RunCommand::class);
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.command.run', 'console.command');
    }

    /**
     * @test
     */
    public function compile_with_valid_config()
    {
        $this->load($this->loadConfig('valid_config.yml'));
        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.source.database', 'zenstruck_backup.source');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.source.files', 'zenstruck_backup.source');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.namer.simple', 'zenstruck_backup.namer');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.namer.daily', 'zenstruck_backup.namer');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.namer.snapshot', 'zenstruck_backup.namer');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.processor.zip', 'zenstruck_backup.processor');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.processor.gzip', 'zenstruck_backup.processor');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.destination.s3', 'zenstruck_backup.destination');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.destination.stream', 'zenstruck_backup.destination');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.profile.daily', 'zenstruck_backup.profile');
        $this->assertContainerBuilderHasServiceDefinitionWithTag('zenstruck_backup.profile.monthly', 'zenstruck_backup.profile');
    }

    /**
     * @test
     * @dataProvider invalidConfigProvider
     */
    public function compile_with_invalid_config(string $file, string $message, string $expectedException)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($message);

        $this->load($this->loadConfig($file));
        $this->compile();
    }

    public static function invalidConfigProvider(): array
    {
        return [
            ['invalid_profile_missing_sources.yml', 'The child config "sources" under "zenstruck_backup.profiles.daily" must be configured.', 'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'],
            ['invalid_profile_missing_namer.yml', 'The child config "namer" under "zenstruck_backup.profiles.daily" must be configured.', 'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'],
            ['invalid_profile_missing_destinations.yml', 'The child config "destinations" under "zenstruck_backup.profiles.daily" must be configured.', 'Symfony\Component\Config\Definition\Exception\InvalidConfigurationException'],
        ];
    }

    protected function getContainerExtensions(): array
    {
        return [new ZenstruckBackupExtension()];
    }

    private function loadConfig($file)
    {
        return Yaml::parse(\file_get_contents(__DIR__.'/../Fixtures/'.$file));
    }
}
