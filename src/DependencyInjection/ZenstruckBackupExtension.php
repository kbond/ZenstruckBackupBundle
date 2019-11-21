<?php

namespace Zenstruck\BackupBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Zenstruck\BackupBundle\DependencyInjection\Factory\Factory;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class ZenstruckBackupExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        $loader->load('destinations.xml');
        $loader->load('namers.xml');
        $loader->load('processors.xml');
        $loader->load('sources.xml');

        $abstractProfile = $container->getDefinition('zenstruck_backup.abstract_profile');

        if (method_exists($abstractProfile, 'setFactory')) {
            // 2.6+
            $abstractProfile->setFactory(array(new Reference('zenstruck_backup.profile_builder'), 'create'));
        } else {
            // <2.6
            $abstractProfile->setFactoryService('zenstruck_backup.profile_builder')
                ->setFactoryMethod('create');
        }

        $container->setDefinition('zenstruck_backup.abstract_profile', $abstractProfile);

        foreach ($config['sources'] as $name => $source) {
            reset($source);

            $configuration
                ->getSourceFactory(key($source))
                ->create($container, $name, reset($source));
        }

        foreach ($config['namers'] as $name => $namer) {
            reset($namer);

            $configuration
                ->getNamerFactory(key($namer))
                ->create($container, $name, reset($namer));
        }

        foreach ($config['processors'] as $name => $processor) {
            reset($processor);

            $configuration
                ->getProcessorFactory(key($processor))
                ->create($container, $name, reset($processor));
        }

        foreach ($config['destinations'] as $name => $destination) {
            reset($destination);

            $configuration
                ->getDestinationFactory(key($destination))
                ->create($container, $name, reset($destination));
        }

        foreach ($config['profiles'] as $name => $profile) {
            $definition = $container->setDefinition(
                sprintf('zenstruck_backup.profile.%s', $name),
                new ChildDefinition('zenstruck_backup.abstract_profile'));

            $definition
                ->replaceArgument(0, $name)
                ->replaceArgument(1, $profile['scratch_dir'])
                ->replaceArgument(2, $profile['processor'])
                ->replaceArgument(3, $profile['namer'])
                ->replaceArgument(4, $profile['sources'])
                ->replaceArgument(5, $profile['destinations'])
                ->addTag('zenstruck_backup.profile');
        }
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     *
     * @return Configuration
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        $tempContainer = new ContainerBuilder();

        $loader = new Loader\XmlFileLoader($tempContainer, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('factories.xml');

        return new Configuration(
            $this->getServices('zenstruck_backup.namer_factory', $tempContainer),
            $this->getServices('zenstruck_backup.processor_factory', $tempContainer),
            $this->getServices('zenstruck_backup.source_factory', $tempContainer),
            $this->getServices('zenstruck_backup.destination_factory', $tempContainer)
        );
    }

    /**
     * @param string           $tag
     * @param ContainerBuilder $container
     *
     * @return Factory[]
     */
    private function getServices($tag, ContainerBuilder $container)
    {
        $services = array();

        foreach (array_keys($container->findTaggedServiceIds($tag)) as $id) {
            /** @var Factory $factory */
            $factory = $container->get($id);

            $services[$factory->getName()] = $factory;
        }

        return $services;
    }
}
