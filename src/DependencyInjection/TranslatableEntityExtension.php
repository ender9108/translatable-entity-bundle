<?php

namespace EnderLab\TranslatableEntityBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TranslatableEntityExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader =new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        foreach ($config as $key => $value) {
            $container->setParameter('translatable_entity.'.$key, $value);
        }
    }

    public function prepend(ContainerBuilder $container)
    {
        $doctrineConfig = [];
        $doctrineConfig['orm']['dql']['string_functions']['JSON_EXTRACT'] = 'Scienta\\DoctrineJsonFunctions\\Query\\AST\\Functions\\Mysql\\JsonExtract';
        $doctrineConfig['orm']['dql']['string_functions']['JSON_SEARCH'] = 'Scienta\\DoctrineJsonFunctions\\Query\\AST\\Functions\\Mysql\\JsonSearch';

        $container->prependExtensionConfig('doctrine', $doctrineConfig);
    }
}