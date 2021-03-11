<?php

namespace Atournayre\AcceptanceBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class AtournayreAcceptanceExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('atournayre_acceptance.is_enabled', $config['is_enabled']);
        $container->setParameter('atournayre_acceptance.start_date_time', $config['start_date_time']);
        $container->setParameter('atournayre_acceptance.end_date_time', $config['end_date_time']);
        $container->setParameter('atournayre_acceptance.template', $config['template']);
        $container->setParameter('atournayre_acceptance.templateError', $config['templateError']);
        $container->setParameter('atournayre_acceptance.noEndDateMessage', $config['noEndDateMessage']);
        $container->setParameter('atournayre_acceptance.dateConversionErrorMessage', $config['dateConversionErrorMessage']);
    }
}
