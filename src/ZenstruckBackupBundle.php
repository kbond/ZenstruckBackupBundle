<?php

namespace Zenstruck\BackupBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\DestinationCompilerPass;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\NamerCompilerPass;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProcessorCompilerPass;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\ProfileCompilerPass;
use Zenstruck\BackupBundle\DependencyInjection\Compiler\SourceCompilerPass;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckBackupBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ProfileCompilerPass());
        $container->addCompilerPass(new DestinationCompilerPass());
        $container->addCompilerPass(new SourceCompilerPass());
        $container->addCompilerPass(new ProcessorCompilerPass());
        $container->addCompilerPass(new NamerCompilerPass());
    }
}
