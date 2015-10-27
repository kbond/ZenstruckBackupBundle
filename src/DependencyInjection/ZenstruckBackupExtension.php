<?php

namespace Zenstruck\BackupBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
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

        $sources = array();
        $namers = array();
        $processors = array();
        $destinations = array();

        foreach ($config['sources'] as $name => $source) {
            reset($source);

            $sources[$name] = $configuration
                ->getSourceFactory(key($source))
                ->create($container, $name, reset($source))
            ;
        }

        foreach ($config['namers'] as $name => $namer) {
            reset($namer);

            $namers[$name] = $configuration
                ->getNamerFactory(key($namer))
                ->create($container, $name, reset($namer))
            ;
        }

        foreach ($config['processors'] as $name => $processor) {
            reset($processor);

            $processors[$name] = $configuration
                ->getProcessorFactory(key($processor))
                ->create($container, $name, reset($processor))
            ;
        }

        foreach ($config['destinations'] as $name => $destination) {
            reset($destination);

            $destinations[$name] = $configuration
                ->getDestinationFactory(key($destination))
                ->create($container, $name, reset($destination))
            ;
        }

        foreach ($config['profiles'] as $name => $profile) {
            $sourcesToAdd = array();

            // validate
            foreach ($profile['sources'] as $source) {
                if (!isset($sources[$source])) {
                    throw new \LogicException(sprintf('Source "%s" is not defined.', $source));
                }

                $sourcesToAdd[] = $sources[$source];
            }

            if (!isset($namers[$profile['namer']])) {
                throw new \LogicException(sprintf('Namer "%s" is not defined.', $profile['namer']));
            }

            $namerToAdd = $namers[$profile['namer']];

            if (!isset($processors[$profile['processor']])) {
                throw new \LogicException(sprintf('Processor "%s" is not defined.', $profile['processor']));
            }

            $processorToAdd = $processors[$profile['processor']];

            $destinationsToAdd = array();

            // validate
            foreach ($profile['destinations'] as $destination) {
                if (!isset($destinations[$destination])) {
                    throw new \LogicException(sprintf('Destination "%s" is not defined.', $destination));
                }

                $destinationsToAdd[] = $destinations[$destination];
            }

            $container->setDefinition(sprintf('zenstruck_backup.manager.%s', $name), new DefinitionDecorator('zenstruck_backup.manager'))
                ->replaceArgument(0, $profile['scratch_dir'])
                ->replaceArgument(1, $processorToAdd)
                ->replaceArgument(2, $namerToAdd)
                ->replaceArgument(3, $sourcesToAdd)
                ->replaceArgument(4, $destinationsToAdd)
                ->addTag('zenstruck_backup.profile', array('alias' => $name))
            ;
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
