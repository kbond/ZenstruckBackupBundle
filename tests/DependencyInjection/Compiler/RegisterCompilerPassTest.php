<?php

namespace Zenstruck\BackupBundle\Tests\DependencyInjection\Compiler;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
abstract class RegisterCompilerPassTest extends AbstractCompilerPassTestCase
{
    /**
     * @test
     */
    public function it_registers_tagged_services()
    {
        $registrarDefinition = new Definition();
        $this->setDefinition($this->getRegistrarDefinitionName(), $registrarDefinition);

        $service = new Definition();
        $service->addTag($this->getTagName());
        $this->setDefinition('my_service', $service);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            $this->getRegistrarDefinitionName(),
            $this->getMethodName(),
            [new Reference('my_service')]
        );
    }

    abstract protected function getRegistrarDefinitionName(): string;

    abstract protected function getTagName(): string;

    abstract protected function getMethodName(): string;
}
