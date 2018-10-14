<?php

namespace Pasttaga\GoogleTranslateBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PasttagaGoogleTranslateExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('methods.yml');

        $container->setParameter('pasttaga_google_translate.api_key', $config['api_key']);

        $this->loadProfilerCollector($container, $loader);
    }

    /**
     * Loads profiler collector for correct environments.
     *
     * @param ContainerBuilder $container Symfony dependency injection container
     * @param XmlFileLoader    $loader    XML file loader
     */
    protected function loadProfilerCollector(ContainerBuilder $container, YamlFileLoader $loader)
    {
        if ($container->getParameter('kernel.debug')) {
            $loader->load('collector.yml');

            $services = $container->findTaggedServiceIds('pasttaga.google_translate.method');
            $identifiers = array_keys($services);

            foreach ($identifiers as $identifier) {
                $serviceDefinition = $container->getDefinition($identifier);
                $serviceDefinition->addArgument(new Reference('debug.stopwatch'));

                $container->setDefinition($identifier, $serviceDefinition);
            }
        }
    }
}
