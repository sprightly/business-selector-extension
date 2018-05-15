<?php

namespace OrangeDigital\BusinessSelectorExtension;

use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;

/**
 * Extension hooks into the Behat DIC and exposes the extensions Initializer.
 * 
 * @author Ben Waine <ben.waine@orange.com>
 * @author Phill Hicks <phillip.hicks@orange.com>  
 */
class Extension implements ExtensionInterface {

    /**
     * Extension configuration ID.
     */
    const BUSINESS_SELECTOR = 'business_selector';

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__)
        );
        
        $loader->load('services.xml');

        $container->setParameter('businessselector.parameters', $config);   
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigKey() {
        return self::BUSINESS_SELECTOR;
    }

    /**
     * Setups configuration for current extension.
     *
     * @param ArrayNodeDefinition $builder
     * 
     * @return void
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->
            children()->
                scalarNode('urlFilePath')->
                    defaultNull()->
                end()->
                scalarNode('selectorFilePath')->
                    defaultNull()->
                end()->
                scalarNode('assetPath')->
                    defaultNull()->
                end()->
                arrayNode('contexts')->
                    children()->
                        scalarNode('UIBusinessSelector')->
                            defaultNull()->
                        end()->
                    end()->
                end()->
            end();
                
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container) {}

    /**
     * {@inheritDoc}
     */
    public function initialize(ExtensionManager $extensionManager) {}
}

return new Extension();