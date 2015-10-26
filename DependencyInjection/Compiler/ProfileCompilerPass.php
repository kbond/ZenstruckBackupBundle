<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ProfileCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('zenstruck_backup.registry')) {
            return;
        }

        $definition = $container->getDefinition(
            'zenstruck_backup.registry'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'zenstruck_backup.profile'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $alias = empty($attributes['alias']) ? $id : $attributes['alias'];
                $definition->addMethodCall(
                    'add',
                    array($alias, new Reference($id))
                );
            }
        }
    }
}
