<?php

namespace Zenstruck\BackupBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class RegisterCompilerPass implements CompilerPassInterface
{
    final public function process(ContainerBuilder $container): void
    {
        $definitionName = $this->getDefinitionName();

        if (false === $container->hasDefinition($definitionName)) {
            return;
        }

        $definition = $container->getDefinition($definitionName);
        $taggedServices = $container->findTaggedServiceIds($this->getTagName());

        foreach (\array_keys($taggedServices) as $id) {
            $definition->addMethodCall($this->getMethodName(), [new Reference($id)]);
        }
    }

    abstract protected function getDefinitionName(): string;

    abstract protected function getTagName(): string;

    abstract protected function getMethodName(): string;
}
