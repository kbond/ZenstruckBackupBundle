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
    public function it_processes_tagged_services()
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
            array(new Reference('my_service'))
        );
    }

    /**
     * @return string
     */
    abstract protected function getRegistrarDefinitionName();

    /**
     * @return string
     */
    abstract protected function getTagName();

    /**
     * @return string
     */
    abstract protected function getMethodName();
}
