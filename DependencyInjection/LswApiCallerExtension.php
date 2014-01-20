<?php

namespace Lsw\ApiCallerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * {@inheritDoc}
 */
class LswApiCallerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('config.yml');
        $loader->load('services.yml');

        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $configs);

        if($defaults = $container->getParameter('defaults')) {
            if(empty($configs['_'])) {
                $configs['_'] = $container->getParameter('_');
            }
            foreach($configs as $key => $config) {
                if($config['replace_engine'] != true) {
                    $configs[$key] = array_replace_recursive($defaults, $config);
                }
            }
        }

        $container->setParameter('api_caller.options', $configs);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'lsw_api_caller';
    }
}
