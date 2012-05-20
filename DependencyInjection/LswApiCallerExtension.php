<?php

namespace Lsw\ApiCallerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Lsw\ApiBundle\Entity\Api;

/**
 * This is the class that loads and manages the bundle configuration
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
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
  }


  public function getAlias()
  { 
    return 'lsw_api_caller';
  }
}
