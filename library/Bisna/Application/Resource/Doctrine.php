<?php

// Zend Framework cannot deal with Resources using namespaces
//namespace Bisna\Application\Resource;

use Bisna\Doctrine\Container as DoctrineContainer;

/**
 * Zend Application Resource Doctrine class
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class Bisna_Application_Resource_Doctrine extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initializes Doctrine Context.
     *
     * @return Bisna\Application\Doctrine\Container
     */
    public function init()
    {
        $config = $this->getOptions();
        
        // Bootstrapping Doctrine autoloaders
        $this->registerClassLoaders($config['classLoader']);
        
        // Starting Doctrine container
        $container = new DoctrineContainer($config);

        // Add to Zend Registry
        \Zend_Registry::set('doctrine', $container);

        return $container;
    }

    /**
     * Register Doctrine class loaders
     *
     * @param array Doctrine Class Loader configuration
     */
    private function registerClassLoaders(array $config = array())
    {
        $classLoaderClass = $config['loaderClass'];
        $classLoaderFile  = $config['loaderFile'];
        
        require_once $classLoaderFile;
        
        $autoloader = \Zend_Loader_Autoloader::getInstance();
        
        foreach ($config['loaders'] as $loaderItem) {
            $classLoader = new $classLoaderClass($loaderItem['namespace'], $loaderItem['includePath']);
            $autoloader->pushAutoloader(array($classLoader, 'loadClass'));
        }
    }
}