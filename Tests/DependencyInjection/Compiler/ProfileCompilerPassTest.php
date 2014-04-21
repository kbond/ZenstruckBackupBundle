<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProfileCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProfileCompilerPassTest extends AbstractCompilerPassTestCase
{
    public function testProcess()
    {
        $registry = new Definition();
        $this->setDefinition('zenstruck_backup.registry', $registry);

        $profile = new Definition();
        $profile->addTag('zenstruck_backup.profile');

        $this->setDefinition('my_profile', $profile);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'zenstruck_backup.registry',
            'add',
            array(
                'my_profile',
                new Reference('my_profile')
            )
        );
    }

    public function testProcessWithAlias()
    {
        $registry = new Definition();
        $this->setDefinition('zenstruck_backup.registry', $registry);

        $profile = new Definition();
        $profile->addTag('zenstruck_backup.profile', array('alias' => 'my_profile_alias'));

        $this->setDefinition('my_profile', $profile);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'zenstruck_backup.registry',
            'add',
            array(
                'my_profile_alias',
                new Reference('my_profile')
            )
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProfileCompilerPass());
    }
}
