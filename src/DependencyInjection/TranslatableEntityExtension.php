<?php

namespace EnderLab\TranslatableEntityBundle\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use EnderLab\TranslatableEntityBundle\Services\CurrentLocaleService;
use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TranslatableEntityExtension extends Extension implements PrependExtensionInterface
{
    private array $formThemes = [
        'translatable_entity_text_field.html.twig',
        'translatable_entity_textarea_field.html.twig',
    ];

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader =new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config/'));
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

        $twigConfig = [];
        $twigConfig['paths'] = ['%kernel.project_dir%/libs/translatable-entity-bundle/templates' => 'TranslatableEntityBundle'];

        $formThemes = [];

        foreach ($this->formThemes as $formTheme) {
            $formThemes[] = '@TranslatableEntityBundle/form/'.$formTheme;
        }

        $twigConfig['form_themes'] = $formThemes;

        $container->prependExtensionConfig('twig', $twigConfig);
    }
}